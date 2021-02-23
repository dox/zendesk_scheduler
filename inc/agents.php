<?php
use Zendesk\API\HttpClient as ZendeskAPI;

class agents {

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


public static function agent($uid = null) {
	global $database;

	$sql  = "SELECT * FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $uid . "' ";
	$sql .= "OR zendesk_id = '" . $uid . "';";

	$results = self::find_by_sql($sql);

	//return $results;
	return !empty($results) ? array_shift($results) : false;
}

public function getAgents($status = "all") {
	global $database;

	$sql  = "SELECT * FROM " . self::$table_name . " ";
	if ($status == "enabled") {
		$sql .= "WHERE enabled = '1' ";
	} elseif ($status == "disabled") {
		$sql .= "WHERE enabled = '0' ";
	} else {
	}

	$sql .= "ORDER BY lastname ASC";

	$agents = self::find_by_sql($sql);

	return $agents;
}

public function displayMembers($status = "enabled") {
	$agents_array = $this->getAgents($status);

	$output  = "<table class=\"table\">";
	$output .= "<thead>";
	$output .= "<tr>";
	$output .= "<th scope=\"col\">Name</th>";
	$output .= "<th>Zendesk ID</th>";
	$output .= "<th>Jobs Logged/Assigned</th>";
	$output .= "<th scope=\"col\"></th>";
	$output .= "</tr>";
	$output .= "</thead>";

	$output .= "<tbody>";

	foreach ($agents_array AS $agent) {
		$agentEditURL = "index.php?n=agent_edit&agentUID=" . $agent->uid;
		$jobsLogged = jobs::jobs_logged($agent->zendesk_id);
		$jobsAssigned = jobs::jobs_assigned($agent->zendesk_id);

		if ($agent->enabled == '1') {
			$rowClass = "";
		} else {
			$rowClass = "table-secondary";
		}

		$output .= "<tr class=\"" . $rowClass . "\">";
		$output .= "<td>" . $agent->firstname . " " . $agent->lastname . "</td>";
		$output .= "<td>" . $agent->zendesk_id . "</td>";
		$output .= "<td><span class=\"badge bg-primary\">" . count($jobsLogged) . "</span> / <span class=\"badge bg-success\">" . count($jobsAssigned) . "<span></td>";
		$output .= "<td><a href=\"" . $agentEditURL . "\">View/Edit</a></td>";
		$output .= "</tr>";
	}

	$output .= "</tbody>";
	$output .= "</table>";

	return $output;
}

public function create() {
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
		$logRecord = new logs();
		$logRecord->description = "Successfully created agent '" . $this->firstname . " " . $this->lastname . "' (" . $this->zendesk_id . ")";
		$logRecord->type = "admin";
		$logRecord->log_record();

		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error creating agent '" . $this->firstname . " " . $this->lastname . "' (" . $this->zendesk_id . ")";
		$logRecord->type = "error";
		$logRecord->log_record();

		return false;
	}
}

public function delete() {
	global $database;

	$sql  = "DELETE FROM " . self::$table_name . " ";
	$sql .= "WHERE uid = '" . $this->uid . "' ";
	$sql .= "LIMIT 1;";

	// check if the database entry was successful (by attempting it)
	if ($database->query($sql)) {
		$logRecord = new logs();
		$logRecord->description = "Successfully deleted agent '" . $this->uid . "'";
		$logRecord->type = "admin";
		$logRecord->log_record();

		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error deleting agent '" . $this->uid . "'";
		$logRecord->type = "error";
		$logRecord->log_record();

		return false;
	}
}

public function update() {
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
		$logRecord = new logs();
		$logRecord->description = "Succesfully updated agent details for " . $this->firstname . " " . $this->lastname;
		$logRecord->type = "admin";
		$logRecord->log_record();

		return true;
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error updating agent details for " . $this->firstname . " " . $this->lastname;
		$logRecord->type = "error";
		$logRecord->log_record();

		return false;
	}
}

public function getZendeskAgents() {
	$subdomain = zd_subdomain;
	$username  = zd_username;
	$token     = zd_token;

	$client = new ZendeskAPI($subdomain);
	$client->setAuth('basic', ['username' => $username, 'token' => $token]);

	$params = array('query' => 'role:Agent role:Administrator');

	try {
    $users = $client->users()->search($params);

		$logRecord = new logs();
		$logRecord->description = "Successfully searched Zendesk for agents";
		$logRecord->type = "info";
		$logRecord->log_record();

		return $users->users;
	} catch (\Zendesk\API\Exceptions\ApiResponseException $e) {
		$logRecord = new logs();
		$logRecord->description = "Error searching Zendesk for agents";
		$logRecord->type = "error";
		$logRecord->log_record();

		return $e->getMessage().'</br>';
	}
}


}
?>
