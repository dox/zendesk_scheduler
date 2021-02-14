<?php
include_once("inc/autoload.php");
if (isset($_POST['inputUsername']) && isset($_POST['inputPassword'])) {
	if ($ldap_connection->auth()->attempt($_POST['inputUsername'] . LDAP_ACCOUNT_SUFFIX, $_POST['inputPassword'], $stayAuthenticated = true)) {
		// Successfully authenticated user.
		$_SESSION['logon'] = true;
		$_SESSION['username'] = strtoupper($_POST['inputUsername']);

		if (in_array(strtolower($_SESSION['username']), $arrayOfAdmins)) {
			$_SESSION['admin'] = true;
		} else {
			$_SESSION['admin'] = false;
		}

		$logRecord = new logs();
		$logRecord->description = $_SESSION['username'] . " logon succesful";
		$logRecord->type = "logon_success";
		$logRecord->log_record();
	} else {
		// Username or password is incorrect.
		$_SESSION['logon'] = false;
		$_SESSION['username'] = null;
		$_SESSION['admin'] = false;
		$_SESSION['logon_error'] = "Incorrect username/password";

		$logRecord = new logs();
		$logRecord->description = $_POST['inputUsername'] . " logon failed";
		$logRecord->type = "logon_fail";
		$logRecord->log_record();
	}
}

if ($_SESSION['logon'] != true) {
	header("Location: logon.php");
	exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Andrew Breakspear">
  <title>Zendesk Scheduler</title>

  <!-- Bootstrap core CSS/JS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</head>

<!--<body>-->
<body class="bg-light">
	<div class="d-flex w-100 h-100 p-3 mx-auto flex-column">
		<?php
		include_once("views/navbar.php");
		?>
		<?php

		$node = "nodes/index.php";
			if (isset($_GET['n'])) {
				$node = "nodes/" . $_GET['n'] . ".php";

				if (!file_exists($node)) {
					$node = "nodes/404.php";
				}
			}
		include_once($node);

		include_once("views/footer.php");
		?>
	</div>
</body>
</html>
