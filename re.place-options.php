<?php

// Check if user can edit themes
if(!current_user_can('edit_themes')) {
	die('Access Denied');
}

require_once('re.place-class.php');
require_once('re.place-restrs.php');

checkInstall();

$base_name = plugin_basename('replace/re.place-options.php');
$options_url = $_SERVER[PHP_SELF] . '?page='.$base_name;

// $options_url = $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);

if ( isset($_REQUEST['action']) ) {
	$action = $_REQUEST['action'];
} else {
	$action = "";
}

switch( $action ) {
	case 'edit':
		showEdit();
		break;
	case 'edit2';
		updateRePlace();
		showMainMenu();
		break;
	case 'new':
		showNewRePlace();
		break;
	case 'new2':
		addRePlace();
		showMainMenu();
		break;
	case 'delete':
		deleteRePlace();
		showMainMenu();
		break;
	case 'updoptions':
		updOptions();
		showMainMenu();
		break;
	default:
		showMainMenu();
}

/**
* Update options
*/
function updOptions() {
	if ( isset($_REQUEST['listview']) ) {
		$listview = $_REQUEST['listview'];
		update_option( 're_place_listview', $listview );
		echo '<div id="message" class="updated fade"><p>Options updated</p></div>';
	}
}


/**
* Show the main options menu
*/
function showMainMenu() {
	global $options_url;
	global $restrictions;
	
	$rePlaceManager = new RePlace();
	$replaces = $rePlaceManager->getRePlaces();

	$listview = get_option('re_place_listview');
	if ( $listview == 'asis' ) {
		$asissel = 'selected';
		$escapedsel = '';
	} else {
		$asissel = '';
		$escapedsel = 'selected';
	}

	$rowcolors = array (
			'#fff',
			'#eee',
		);
	$rowcolors_count = count($rowcolors);
	$current_color = 0;
	
?>
<div class="wrap"> 
	<h2><?php _e('re.place'); ?></h2> 

	<h3>List view options:</h3>
	<form name="re_edit" method="post" action="<?php echo $options_url; ?>">
		<select name="listview">
			<option value="escaped" <?php echo $escapedsel; ?>>Escape HTML characters</option>
			<option value="asis" <?php echo $asissel; ?>>Dont escape HTML characters</option>
		</select>
		<input type="hidden" name="action" value="updoptions" />
		<input type="submit" value="<?php _e('Update'); ?>">
	</form>

	<h3><?php _e('Entries'); ?> (<a href="<?php echo $options_url; ?>&amp;action=new"><?php _e('Add new'); ?></a>):</h3>
	<?php if(is_array($replaces)) { ?>
	<table border="0" cellpadding="3" width="100%">
		<tr>
			<th><?php _e('ID'); ?></th>
			<th align='left'><?php _e('Description'); ?></th>
			<th><?php _e('Active'); ?></th>
			<th><?php _e('Search for'); ?></th>
			<th><?php _e('Replace with'); ?></th>
			<th><?php _e('Order'); ?></th>
			<th><?php _e('Restriction'); ?></th>
			<th><?php _e('Otherwise'); ?></th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach($replaces as $re_place) { 
				$class = ('alternate' == $class) ? '' : 'alternate';
		?>
		<tr class='<?php echo $class; ?>' style="background: <?php echo $rowcolors[$current_color++%$rowcolors_count]; ?>">
			<td><?php echo $re_place->re_id;?></td>
			<td style="font-size: 80%;"><?php echo $re_place->re_description;?></td>
			<td align="center"><?php echo $re_place->re_active;?></td>
			<td style="font-size: 80%;"><?php echo $listview=='asis' ? $re_place->re_search : htmlspecialchars($re_place->re_search); ?></td>
			<td style="font-size: 80%;"><?php echo $listview=='asis' ? $re_place->re_place : htmlspecialchars($re_place->re_place); ?></td>
			<td align="center"><?php echo $re_place->re_order;?></td>
			<td style="font-size: 80%;"><?php echo $restrictions[$re_place->restriction]; ?></td>
			<td style="font-size: 80%;"><?php echo $listview=='asis' ? $re_place->restr_otherwise : htmlspecialchars($re_place->restr_otherwise); ?></td>
			<td style="font-size: 80%;"><a href="<?php echo $options_url; ?>&amp;action=edit&amp;id=<?php echo $re_place->re_id;?>" class="edit"><?php _e('Edit'); ?></a></td>
			<td style="font-size: 80%;"><a href="<?php echo $options_url; ?>&amp;action=delete&amp;id=<?php echo $re_place->re_id;?>" class="delete"><?php _e('Delete'); ?></a></td>
		</tr>	
		<?php }  ?>
	</table>
	
	<a href="<?php echo $options_url; ?>&amp;action=new"><?php _e('Add new entry'); ?></a><br />
	<?php } else { ?>
		You have not defined any entries yet. <a href="<?php echo $options_url; ?>&amp;action=new">Add a new entry</a> to begin using re.place.
	<?php } ?>

	</table>
<br /><br />
</div>

<?php
} // function showMainMenu


/**
* Show the entry edit page
*/
function showEdit() {
	global $options_url;
	global $restrictions;

	if ( isset($_REQUEST['id']) ) {
		$re_id = $_REQUEST['id'];
	} else {
		$re_id = "";
	}
	$rePlaceManager = new RePlace();
	$re_place = $rePlaceManager->getRePlace($re_id);
?>
<div class="wrap"> 
	<h2><?php _e('re.place -- edit entry'); ?></h2> 
	
	<form name="re_edit" method="post" action="<?php echo $options_url; ?>">
	<input type="hidden" name="action" value="edit2" />
	<input type="hidden" name="re_id" value="<?php echo $re_place->re_id;?>" />
	<table cellspacing="3">
		<tr>
			<td valign="top">ID</td>
			<td><?php echo $re_place->re_id;?></td>
		</tr>
		<tr>
			<td valign="top">Description</td>
			<td>
				<input name="re_description" type="text" size="50" value="<?php echo htmlspecialchars($re_place->re_description);?>" /><br />
				Any text that helps you identify this entry
			</td>
		</tr>
		<tr>
			<td valign="top">Search for</td>
			<td>
				<textarea name="re_search" rows="6" cols="80"><?php echo htmlspecialchars($re_place->re_search);?></textarea><br />
				What is re.place should search for.
			</td>
		</tr>
		<tr>
			<td valign="top">Replace with</td>
			<td>
				<textarea name="re_place" rows="6" cols="80"><?php echo htmlspecialchars($re_place->re_place);?></textarea><br />
				What re.place should place instead.
			</td>
		</tr>
		<tr>
			<td valign="top">Restriction</td>
			<td>
				Replace only if...
				<select name="restriction">
					<?php foreach ( $restrictions as $r => $restr ) {
						$sel = $re_place->restriction ?
							( $r == $re_place->restriction ? ' selected' : '' ) :
							( $r == 'none' ? ' selected' : '' );
						echo '<option' . $sel . ' value="' . $r . '">' . __($restr) . '</option>' ."\n";
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">Otherwise replace with:</td>
			<td>
				<textarea name="restr_otherwise" rows="6" cols="80"><?php echo htmlspecialchars($re_place->restr_otherwise);?></textarea><br />
			</td>
		</tr>
		<tr>
			<td valign="top">Order</td>
			<td>
				<input name="re_order" type="text" size="5" value="<?php echo htmlspecialchars($re_place->re_order);?>"><br />
				Order of this entry
			</td>
		</tr>
		<tr>
			<td valign="top">Active</td>
			<td>
				<input name="re_active" type="checkbox" value="Y" <?php echo ($re_place->re_active == "Y" ? "checked" : "");?> />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="submit" value="<?php _e('Save'); ?>" /></td>
		</tr>
	</table>		
	
	</form>
</div>
<?php
} // function showEdit

/**
* Update an entry, gets the input from the "edit" form
*/
function updateRePlace() {
	global $options_url;

	$re_place= array();
	$re_place["re_id"] = $_REQUEST["re_id"];
	$re_place["re_description"] = $_REQUEST["re_description"];
	$re_place["re_search"] = $_REQUEST["re_search"];
	$re_place["re_place"] = $_REQUEST["re_place"];
	$re_place["re_order"] = $_REQUEST["re_order"];
	$re_place["re_active"] = $_REQUEST["re_active"];
	$re_place["restriction"] = $_REQUEST["restriction"];
	$re_place["restr_otherwise"] = $_REQUEST["restr_otherwise"];
	// if (get_magic_quotes_gpc()) {
		foreach($re_place as $key => $value) {
			$re_place[$key] = stripslashes($value);
		}
   	// } 
   	
	$rePlaceManager= new RePlace();
	$replaces = $rePlaceManager->updateRePlace($re_place);

	echo '<div id="message" class="updated fade"><p>re.place updated</p></div>';
}

/**
* Show the new entry page
*/
function showNewRePlace() {
	global $options_url;
	global $restrictions;

?>
<div class="wrap"> 
	<h2><?php _e('re.place -- new entry'); ?></h2> 
	
	<form name="re_edit" method="post" action="<?php echo $options_url; ?>">
	<input type="hidden" name="action" value="new2" />
	<table cellspacing="3">
		<tr>
			<td valign="top">Description</td>
			<td>
				<input name="re_description" type="text" size="50" value="" /><br />
				Any text that helps you identify this re.place
			</td>
		</tr>
		<tr>
			<td valign="top">Search for</td>
			<td>
				<textarea name="re_search" rows="6" cols="80"></textarea><br />
				What is re.place should search for.
			</td>
		</tr>
		<tr>
			<td valign="top">Replace with</td>
			<td>
				<textarea name="re_place" rows="6" cols="80"></textarea><br />
				What re.place should place instead.
			</td>
		</tr>
		<tr>
			<td valign="top">Restriction</td>
			<td>
				Replace only if...
				<select name="restriction">
					<?php foreach ( $restrictions as $r => $restr ) {
						$sel = // $re_place->restriction ?
							// ( $r == $re_place->restriction ? ' selected' : '' ) :
							( $r == 'none' ? ' selected' : '' );
						echo '<option' . $sel . ' value="' . $r . '">' . __($restr) . '</option>' ."\n";
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">Otherwise replace with:</td>
			<td>
				<textarea name="restr_otherwise" rows="6" cols="80"></textarea><br />
			</td>
		</tr>
		<tr>
			<td valign="top">Order</td>
			<td>
				<input name="re_order" type="text" value="0"><br />
				Order of this entry
			</td>
		</tr>
		<tr>
			<td valign="top">Active</td>
			<td>
				<input name="re_active" type="checkbox" value="Y" checked />
			</td>
		</tr>
                <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="submit" value="<?php _e('Save'); ?>" /></td>
                </tr>
	</table>
	</form>
</div>
<?php
} // function showNewRePlace

/**
* Add an entry, gets the input from the "new entry" form
*/
function addRePlace() {
	global $options_url;

	$re_place = array();
	$re_place["re_id"] = $_REQUEST["re_id"];
	$re_place["re_description"] = $_REQUEST["re_description"];
	$re_place["re_active"] = $_REQUEST["re_active"];
	$re_place["re_search"] = $_REQUEST["re_search"];
	$re_place["re_place"] = $_REQUEST["re_place"];
	$re_place["re_order"] = $_REQUEST["re_order"];
	$re_place["restriction"] = $_REQUEST["restriction"];
	$re_place["restr_otherwise"] = $_REQUEST["restr_otherwise"];
	// if (get_magic_quotes_gpc()) {
		foreach($re_place as $key => $value) {
			$re_place[$key] = stripslashes($value);
		}
   	// } 
	
	$rePlaceManager = new RePlace();
	$replaces = $rePlaceManager->addRePlace($re_place);

	echo '<div id="message" class="updated fade"><p>re.place added</p></div>';
}

/**
* Delete an entry 
*/
function deleteRePlace() {
	global $options_url;

	$re_id = $_REQUEST["id"];
	
	if($re_id > 0) {
		$rePlaceManager = new RePlace();
		$replaces = $rePlaceManager->deleteRePlace($re_id);
	
		echo '<div id="message" class="updated fade"><p>re.place deleted</p></div>';
	}
}

/**
* Check if re.place is installed
*/
function checkInstall() {
	global $wpdb;
	global $table_prefix;		
	
	$version = get_option('re_place_version');

	if($version == "") {
		$sql = <<<SQL
			CREATE TABLE `{$table_prefix}re_place` (
			  `re_id` bigint(20) NOT NULL auto_increment,
			  `re_active` char(1) NOT NULL default 'Y',
			  `re_description` varchar(255) NOT NULL default '',
			  `re_search` mediumtext NOT NULL,
			  `re_place` mediumtext NOT NULL,
			  `re_order` int NOT NULL default 0,
			  `restriction` varchar(32),
			  `restr_otherwise` mediumtext,
			  PRIMARY KEY  (`re_id`)
			) ENGINE=MyISAM ;
SQL;
		$wpdb->query($sql);
		add_option( 're_place_version', '0.2.1', 're.place version number', 'yes' );
		add_option( 're_place_listview', 'asis', 're.place list view option', 'yes' );
	} else {
		switch ( $version ) {
			case '0.1.1':
			case '0.1.2':
				addRestrColumns();
				updateVersion();
				add_option( 're_place_listview', 'asis', 're.place list view option', 'yes' );
				break;
			case '0.1.3':
			case '0.1.4':
			case '0.1.5':
				updateVersion();
				add_option( 're_place_listview', 'asis', 're.place list view option', 'yes' );
				break;
			case '0.2.0':
				updateVersion();
		}
	}
}

function addRestrColumns() {
	global $wpdb;
	global $table_prefix;		
	$sql = <<<SQLU
		ALTER TABLE `{$table_prefix}re_place`
			ADD COLUMN `restriction` varchar(32),
			ADD COLUMN `restr_otherwise` mediumtext;
SQLU;
	$wpdb->query($sql);
}

function updateVersion() {
	update_option( 're_place_version', '0.2.1' );
}

?>
