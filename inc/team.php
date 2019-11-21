<?php
class team {

protected static $table_name = "team";

public $uid;
public $firstname;
public $lastname;
public $email;
public $zendesk_id;
public $enabled;


private static function instantiate($record) {
	$object = new self;
	
	foreach ($record as $attribute=>$value) {
		if ($object->has_attribute($attribute)) {
			$object->$attribute = $value;
		}
	}
	
	return $object;
}

private function has_attribute($attribute) {
	// get_object_vars returns as associative array with all attributes
	// (incl. private ones!) as the keys and their current values as the value
	$object_vars = get_object_vars($this) ;
	
	// we don't care about the value, we just want to know if the key exists
	// will return true or false
	return array_key_exists($attribute, $object_vars);
}



// ****** //

public static function find_by_sql($sql="") {
	global $database;
	
	$result_set = $database->query($sql);
	$object_array = array();
	
	while ($row = $database->fetch_array($result_set)) {
		global $database;
		$object_array[] = self::instantiate($row);
	}
	
	return $object_array;
}


public static function member($uid = null) {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $uid . "' ";
	$sql .= "OR zendesk_id = '" . $uid . "';";
	
	$results = self::find_by_sql($sql);
	
	//return $results;
	return !empty($results) ? array_shift($results) : false;
}

public static function team_all() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function team_all_enabled() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE enabled = '1' ";
	$sql .= "ORDER BY lastname ASC";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function team_all_disabled() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE enabled = '0';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public function member_display() {
	global $assignUsers;
	
	$output  = "<div class=\"alert alert-info\">";
	$output .= "<b>" . $this->firstname . " " . $this->lastname . "</b>";
	$output .= " <span class=\"badge badge-secondary\">" . $this->jobs_count() . "</span>";
	$output .= " <i>(" . $this->email . ")</i>";
	$output .= "<a href=\"team_edit.php?member=" . $this->uid . "\" class=\"btn btn-outline-primary btn-sm float-right\">Modify</a>";
	$output .= "</div>";
	
	return $output;
}

public function team_create() {
	global $database;

	$sql  = "INSERT INTO " . self::$table_name . " (";
	$sql .= "firstname, lastname, email, zendesk_id, enabled";
	$sql .= ") VALUES ('";
	$sql .= $database->escape_value($this->firstname) . "', '";
	$sql .= $database->escape_value($this->lastname) . "', '";
	$sql .= $database->escape_value($this->email) . "', '";
	$sql .= $database->escape_value($this->zendesk_id) . "', '";
	$sql .= $database->escape_value($this->enabled) . "')";
	
	// check if the database entry was successful (by attempting it)
	if ($database->query($sql)) {
		return true;
	} else {
		return false;
	}
}

public function member_delete() {
	global $database;
	
	$sql  = "DELETE FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $this->uid . "' ";
	$sql .= "LIMIT 1;";
	
	// check if the database entry was successful (by attempting it)
	if ($database->query($sql)) {
		return true;
	} else {
		return false;
	}
}

public function jobs_count() {
	global $database;
	
	$jobs = new jobs();
	$jobsAssigned = $jobs->jobs_assigned($this->zendesk_id);
	
	return count($jobsAssigned);
}

public function member_update() {
	global $database;
	
	$sql  = "UPDATE " . self::$table_name . " ";
	$sql .= "SET firstname = '" . $database->escape_value($this->firstname) . "', ";
	$sql .= "lastname = '" . $database->escape_value($this->lastname) . "', ";
	$sql .= "email = '" . $database->escape_value($this->email) . "', ";
	$sql .= "zendesk_id = '" . $database->escape_value($this->zendesk_id) . "', ";
	$sql .= "enabled = '" . $database->escape_value($this->enabled) . "' ";
	$sql .= "WHERE uid = '" . $this->uid . "' ";
	$sql .= "LIMIT 1;";
	
	if ($database->query($sql)) {
		return true;
	} else {
		return false;
	}
}


}
?>