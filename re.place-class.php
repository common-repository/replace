<?php

/**
* Classes for re.place plugin
*/

class RePlace{
	
	var $re_table;

	/**
	* Constructor
	*/
	function RePlace() {
		global $table_prefix;
		$this->re_table = $table_prefix . "re_place";
	}

	/**
	* Get all entries in the database
	*/
	function getRePlaces() {
		global $wpdb;
		
		$sql = "SELECT * FROM ".$this->re_table." WHERE 1 "; 
		$sql .= " ORDER BY re_id ASC ";
		
		$replaces = $wpdb->get_results($sql);
		return $replaces;
	}

	/**
	 * Get all paris "search->replace":
	 */
	function getRePairs() {
		global $wpdb;
		
		$sql = "SELECT re_search, re_place, restriction, restr_otherwise FROM ".$this->re_table." WHERE re_active='Y' "; 
		$sql .= " ORDER BY re_order, re_id ASC ";
		
		$replaces = $wpdb->get_results($sql);
		return $replaces;
	}

	/**
	* Get data for a search--replace pair with a given re_id:
	*/
	function getRePlace($re_id) {
		global $wpdb;
		
		$sql = "SELECT * FROM ".$this->re_table." WHERE re_id = '$re_id' "; 
		$replaces = $wpdb->get_results($sql);
		return $replaces[0];
	}

	/**
	* Update data for an entry:
	*/
	function updateRePlace($re_place) {
		global $wpdb;
	
		if($re_place["re_active"] != "Y") {
			$re_place["re_active"] = "N";
		}
		$sql = "UPDATE " . $this->re_table . " SET "
			." re_description = '" . $wpdb->escape($re_place["re_description"]) . "', "
			." re_search = '" . $wpdb->escape($re_place["re_search"]) . "', "
			." re_place = '" . $wpdb->escape($re_place["re_place"]) . "', "
			." re_active = '" . $re_place["re_active"] . "', "
			." re_order = '" . $re_place["re_order"] . "', "
			." restriction = '" . $re_place["restriction"] . "', "
			." restr_otherwise = '" . $wpdb->escape($re_place["restr_otherwise"]) . "' "
			." WHERE re_id = '" . $re_place["re_id"] . "' ";
		$wpdb->query($sql);
	}

	/**
	* Add a new entry to the database:
	*/
	function addRePlace($re_place) {
		global $wpdb;
	
		if($re_place["re_active"] != "Y") {
			$re_place["re_active"] = "N";
		}
		$sql = "INSERT INTO " . $this->re_table . " SET "
			." re_description = '" . $wpdb->escape($re_place["re_description"]) . "', "
			." re_search = '" . $wpdb->escape($re_place["re_search"]) . "', "
			." re_place = '" . $wpdb->escape($re_place["re_place"]) . "', "
			." re_order = '" . $re_place["re_order"] . "', "
			." re_active = '" . $re_place["re_active"] . "', "
			." restriction = '" . $re_place["restriction"] . "', "
			." restr_otherwise = '" . $wpdb->escape($re_place["restr_otherwise"]) . "'";
		$wpdb->query($sql);
	}

	/**
	* Delete an entry from the database:
	*/
	function deleteRePlace($re_id) {
		global $wpdb;

		$sql = "DELETE FROM " . $this->re_table . " WHERE re_id = '".$re_id."' ";	
		$wpdb->query($sql);
	}

}

?>
