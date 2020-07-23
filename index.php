<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

<body>
<?php include_once("views/navbar.php"); ?>
<div class="container">
	<?php include_once("views/jumbotron.php"); ?>

	<div class="row" role="main">
		<div class="col-lg-6">
			<h1>Step 1:</h1>
			<p>Make sure you have installed the excellent <a href="https://github.com/zendesk/zendesk_api_client_php">Zendesk API Client</a> into the same directory as the above files using composer</p>

			<h1>Step 2</h1>
			<p>Ensure you have configured cron to run the necessary files (see the README)</p>

			<h1>Step 3</h1>
			<p>Setup your Zendesk <a href="agents.php">agents</a>.</p>
		</div>

		<div class="col-lg-6">
			<h1>Step 4</h1>
			<p>Setup your Zendesk <a href="jobs.php">tasks</a>.</p>

			<h1>Step 6</h1>
			<p>Sit back and wait for the jobs to come in!</p>
		</div>
	</div>

	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>
