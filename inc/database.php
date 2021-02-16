<?php
class database {

	private $connection;

	function __construct() {
		$this->open_connection();
	}
	public function open_connection() {
		$this->connection = mysqli_connect(db_host, db_user, db_password);
		if (!$this->connection) {
			die ("Database connection failed" . mysqli_error());
		} else {
			$db_select = mysqli_select_db($this->connection, db_database);
			if (!$db_select) {
				die ("Database selec3tion failed: " . mysqli_error());
			}
		}
	}

	public function close_connection() {
		if(isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

	public function query($sql) {
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);

		return $result;
	}

	private function confirm_query($result) {
		if (!$result) {
			die ("Database query failed: " . mysqli_error());
			//echo ("Database query failed: " . mysql_error());
		}
	}

	public function escape_value($value) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists("mysql_real_escape_string"); // i.e PHP >= 4.3.0
		if ($new_enough_php) { // PHP v4.3.0 or higher
			//undo any magic quote effects so mysql_real_escape_string can do thw work
			if ($magic_quotes_active) {
				$value = stripcslashes($value);
				$value = mysql_real_escape_string($value);
			} else { //before PHP v4.3.0
				//if magic quotes aren't already on then add slashes manually
				if (!$magic_quotes_active) {
					$value = addslashes($value);
				}
			}
			// if magic quotes are active, then the slashes already exist
			}
	return $value;
	}

	// "database-neutral" methods

	public function fetch_array ($result_set) {
		return mysqli_fetch_array($result_set);
	}

	public function num_rows ($result_set) {
		return mysqli_num_rows($result_set);
	}

	public function insert_id () {
		return mysqli_insert_id($this->connection);
	}

	public function affected_rows () {
		return mysqli_affected_rows($this->connection);
	}
}
$database = new database();

function autoPluralise ($singular, $plural, $count = 1) {
	// fantasticly clever function to return the correct plural of a word/count combo
	// Usage:	$singular	= single version of the word (e.g. 'Bus')
	//       	$plural 	= plural version of the word (e.g. 'Busses')
	//			$count		= the number you wish to work out the plural from (e.g. 2)
	// Return:	the singular or plural word, based on the count (e.g. 'Jobs')
	// Example:	autoPluralise("Bus", "Busses", 3)  -  would return "Busses"
	//			autoPluralise("Bus", "Busses", 1)  -  would return "Bus"

	return ($count == 1)? $singular : $plural;
} // END function autoPluralise

function printArray($array) {
	echo ("<pre>");
	print_r ($array);
	echo ("</pre>");
}
?>
