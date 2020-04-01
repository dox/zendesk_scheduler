<?php
$rootDir = dirname(__DIR__, 1);
include($rootDir . "/vendor/autoload.php");
use Zendesk\API\HttpClient as ZendeskAPI;

class jobs {

protected static $table_name = "jobs";

public $uid;
public $subject;
public $body;
public $type;
public $priority;
public $tags;
public $frequency;
public $frequency2;
public $assign_to;
public $logged_by;
public $cc;
public $status;

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


public static function job($uid = null) {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $uid . "';";
	
	$results = self::find_by_sql($sql);
	
	//return $results;
	return !empty($results) ? array_shift($results) : false;
}

public static function jobs_all() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . ";";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_daily() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE frequency = 'Daily';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_weekly() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE frequency = 'Weekly';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_monthly() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE frequency = 'Monthly';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_yearly() {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE frequency = 'Yearly';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_assigned($zendesk_id = null) {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE assign_to = '" . $zendesk_id . "';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
	//return !empty($results) ? array_shift($results) : false;
}

public static function jobs_involved_with($zendesk_id = null) {
	global $database;
	
	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE assign_to = '" . $zendesk_id . "' ";
	$sql .= "OR logged_by = '" . $zendesk_id . "';";
	
	$results = self::find_by_sql($sql);
	
	return $results;
}

public function job_display() {
	$team = new team();
	$teamMember = $team->member($this->assign_to);
	
	if ($this->status == "Enabled") {
		$class = "alert-info";
	} else {
		$class = "alert-secondary";
	}
	
	$output  = "<div class=\"alert " . $class . " clearfix\" role=\"alert\">";
	$output .= "<b>" . $this->type . ":</b> ";
	$output .= $this->subject;
	$output .= " <i>(" . $teamMember->firstname . " " . $teamMember->lastname . ")</i>";
	$output .= "<a href=\"job_edit.php?job=" . $this->uid . "\" class=\"btn btn-outline-primary btn-sm float-right\">Modify</a>";
	$output .= "</div>";
	
	return $output;
}

public function job_create() {
	global $database;

	$sql  = "INSERT INTO " . self::$table_name . " (";
	$sql .= "subject, body, type, priority, tags, frequency, frequency2, assign_to, cc, status, logged_by";
	$sql .= ") VALUES ('";
	$sql .= $database->escape_value($this->subject) . "', '";
	$sql .= $database->escape_value($this->body) . "', '";
	$sql .= $database->escape_value($this->type) . "', '";
	$sql .= $database->escape_value($this->priority) . "', '";
	$sql .= $database->escape_value($this->tags) . "', '";
	$sql .= $database->escape_value($this->frequency) . "', '";
	$sql .= $database->escape_value($this->frequency2) . "', '";
	$sql .= $database->escape_value($this->assign_to) . "', '";
	$sql .= $database->escape_value($this->cc) . "', '";
	$sql .= $database->escape_value($this->status) . "', ";
	$sql .= $database->escape_value($this->logged_by) . "')";
	
	// check if the database entry was successful (by attempting it)
	if ($database->query($sql)) {
		$logRecord = new logs();
		$logRecord->description = "New " . $this->frequency . " task created: '" . $this->subject . "'";
		$logRecord->type = "admin";
		$logRecord->log_record();
	
		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error creating task: " . $this->subject . "'";
		$logRecord->type = "error";
		$logRecord->log_record();
		
		return false;
	}
}

public function job_delete() {
	global $database;
	
	$sql  = "DELETE FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $this->uid . "' ";
	$sql .= "LIMIT 1;";
	
	// check if the database entry was successful (by attempting it)
	if ($database->query($sql)) {
		$logRecord = new logs();
		$logRecord->description = "Deleting task (" . $this->uid . ")";
		$logRecord->type = "admin";
		$logRecord->log_record();
		
		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error deleting task (" . $this->uid . ")";
		$logRecord->type = "error";
		$logRecord->log_record();
		
		return false;
	}
}

public function job_update() {
	global $database;

	$sql  = "UPDATE " . self::$table_name . " ";
	$sql .= "SET subject = '" . $database->escape_value($this->subject) . "', ";
	$sql .= "body = '" . $database->escape_value($this->body) . "', ";
	$sql .= "type = '" . $database->escape_value($this->type) . "', ";
	$sql .= "priority = '" . $database->escape_value($this->priority) . "', ";
	$sql .= "tags = '" . $database->escape_value($this->tags) . "', ";
	$sql .= "frequency = '" . $database->escape_value($this->frequency) . "', ";
	$sql .= "frequency2 = '" . $database->escape_value($this->frequency2) . "', ";
	$sql .= "logged_by = '" . $database->escape_value($this->logged_by) . "', ";
	$sql .= "cc = '" . $database->escape_value($this->cc) . "', ";
	$sql .= "assign_to = '" . $database->escape_value($this->assign_to) . "', ";
	$sql .= "status = '" . $database->escape_value($this->status) . "' ";
	$sql .= "WHERE uid = '" . $this->uid . "' ";
	$sql .= "LIMIT 1;";
	
	if ($database->query($sql)) {
		$logRecord = new logs();
		$logRecord->description = "Updating task (" . $this->uid . ")";
		$logRecord->type = "admin";
		$logRecord->log_record();
		
		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error updating task (" . $this->uid . ")";
		$logRecord->type = "error";
		$logRecord->log_record();
		
		return false;
	}
}

public function tagsArray() {
	$tags = $this->tags;
	$tags = str_replace(" ", "", $tags); // remove spaces
	$tagsArray = explode(",", $this->tags);
	
	return $tagsArray;
}

public function create_zendesk_ticket() {
	$subdomain = "stedmundhall";
	$username  = "andrew.breakspear@seh.ox.ac.uk";
	$token     = "17bSm1BzX5lPBq3c7RUBMIH30gc4huvQBw6RbbJr";
	
	$client = new ZendeskAPI($subdomain);
	$client->setAuth('basic', ['username' => $username, 'token' => $token]);
	
	if ($this->assign_to == 0) {
		$this->assign_to = null;
	}
	
	try {
		// Create a new ticket wi
		$newTicket = $client->tickets()->create(array(
			'type' => strtolower($this->type),
			'tags'  => array( implode(",", $this->tagsArray()) ),
			'subject'  => $this->subject,
			'comment'  => array(
				'body' => $this->body
			),
			'priority' => strtolower($this->priority),
			'assignee_id' => $this->assign_to,
			'requester_id' => $this->logged_by,
			'collaborators' => $this->cc,
		));
		
		$logRecord = new logs();
		$logRecord->description = "Successfully ran " . strtolower($this->frequency) . " task  '" . $this->subject . "' (" . $this->uid . ")";
		$logRecord->type = "cron";
		$logRecord->log_record();
		
		echo "running complete";
		
		// Show result
		echo "<pre>";
		print_r($newTicket);
		echo "</pre>";
	} catch (\Zendesk\API\Exceptions\ApiResponseException $e) {
		$logRecord = new logs();
		$logRecord->description = "Error running " . $this->frequency . "task  '" . $this->subject . "' (" . $this->uid . ") " . $e->getMessage();
		$logRecord->type = "error";
		$logRecord->log_record();
		
		echo $e->getMessage().'</br>';
	}
}


}
?>