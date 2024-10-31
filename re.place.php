<?php
/* 
Plugin Name: re.place
Version: 0.2.1
Plugin URI: http://brownian.org.ua/?page_id=61
Description: Custom regexp search and replace contents.
Author: Dmytro O. Redchuk
Author URI: http://brownian.org.ua/

*/

/*

Copyright 2008 Dmytro O. Redchuk (email: brownian.box@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/ 

/*
The code was directly taken from WPAds plugin
by Nick Brady, http://thesandbox.wordpress.com/ .

And modified.
*/


// error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once( 're.place-class.php' );

/**
 * Get pairs of serach--replace fields:
 */
function get_re_pairs(){
	global $post;
	//print $post->ID;
        $rePlaceManager = new RePlace();
        $re_pairs = $rePlaceManager->getRePairs();
	return $re_pairs;
}

/**
* Content filter: 
*/
function re_place_content_filter($content) {
	global $user_ID;

	$pairs = get_re_pairs();

	$search = array();
	$place  = array();

	foreach ($pairs as $re_place) {
		$search[] = "/" . $re_place->re_search . "/";

		if ( $re_place->restriction == 'auth' && !$user_ID ) {
				$place[] = $re_place->restr_otherwise;

		} else if ( $re_place->restriction == 'page' && !is_page() ) {
				$place[] = $re_place->restr_otherwise;

		} else if ( $re_place->restriction == 'post' && is_page() ) {
				$place[] = $re_place->restr_otherwise;

		} else {
			$place[] = $re_place->re_place;
		}
	}

	$content = preg_replace($search, $place, $content);

	return $content;
}

// re.place menu:
add_action('admin_menu', 're_place_menu');

function re_place_menu() {
	if (function_exists('add_submenu_page')) {
		add_submenu_page('options-general.php', __('re.place'), __('re.place'), 'edit_themes', 'replace/re.place-options.php');
	}
}

// add content filter:
if(function_exists('add_filter')) {
	add_filter('the_content', 're_place_content_filter'); 
}

?>
