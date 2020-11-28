<?php
	include_once("inc/autoload.php");

	if (isset($_GET['logout'])) {
	  $_SESSION['logon'] = false;
	}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="SCR Meal Booking system for St Edmund Hall">
  <meta name="author" content="Andrew Breakspear">
  <title>Zendesk Scheduler</title>

  <link rel="canonical" href="https://v5.getbootstrap.com/docs/5.0/examples/cover/">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">

  <!-- Favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
  <link rel="manifest" href="/img/favicons/site.webmanifest">
	<link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="icon" href="/img/favicons/favicon.ico">
  <meta name="theme-color" content="#7952b3">

	<style>
	body {

	  background: url('/views/cover.jpg') no-repeat center center fixed;
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	.form-signin {
	  width: 100%;
	  max-width: 330px;
	  padding: 15px;
	  margin: auto;
	}
	.form-signin .checkbox {
	  font-weight: 400;
	}
	.form-signin .form-control {
	  position: relative;
	  box-sizing: border-box;
	  height: auto;
	  padding: 10px;
	  font-size: 16px;
	}
	.form-signin .form-control:focus {
	  z-index: 2;
	}
	.form-signin input[type="text"] {
	  margin-bottom: -1px;
	  border-bottom-right-radius: 0;
	  border-bottom-left-radius: 0;
	}
	.form-signin input[type="password"] {
	  margin-bottom: 10px;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	}
	</style>
</head>

<body class="d-flex h-100 text-center bg-dark">
	<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
		</header>

		<main class="form-signin">
			<?php
			if (isset($_SESSION['logon_error'])) {
				echo "<div class=\"alert alert-primary\" role=\"alert\">";
				echo $_SESSION['logon_error'] . " <a href=\"" . reset_url . "\" class=\"alert-link\">Forgot your password?</a>";
				echo "</div>";
			}
			?>
	    <form method="post" id="loginSubmit" action="index.php">
	      <div class="mb-4 text-center">
					<svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-calendar2-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
						<path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
						<path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
					</svg>
	        <h1 class="h3 mb-3 font-weight-normal">Zendesk Scheduler</h1>
	      </div>
	      <label for="inputUsername" class="visually-hidden">Username</label>
	      <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus>
	      <label for="inputPassword" class="visually-hidden">Password</label>
	      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
	      <button class="btn btn-lg btn-primary btn-block" type="submit">Log In</button>
	      <p class="mt-5 mb-3  text-center"><a href="<?php echo reset_url; ?>">Forgot your password?</a></p>
	    </form>
	  </main>
		<footer class="mt-auto">
			<p><a href="https://github.com/dox/zendesk_scheduler">Zendesk Scheduler</a> developed by <a href="https://github.com/dox">Andrew Breakspear</a></p>
		</footer>
    <?php
			$_SESSION['logon_error'] = null;
		?>
	</div>
</body>
</html>
