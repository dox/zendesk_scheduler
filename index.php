<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

<body>
<?php include_once("views/navbar.php"); ?>
<div class="container">	
	<?php include_once("views/jumbotron.php"); ?>
	
	<div class="row">
		<div class="col-lg-6">
			<h4>Step 1:</h4>
			<p>Make sure you have installed the excellent <a href="https://github.com/zendesk/zendesk_api_client_php">Zendesk API Client</a> into the same directory as the above files using composer</p>
			
			<h4>Step 2</h4>
			<p>Ensure you have configured cron to run the necessary files (see the README)</p>
			
			<h4>Step 3</h4>
			<p>Setup your Zendesk <a href="agents.php">agents</a>.</p>
		</div>

		<div class="col-lg-6">
			<h4>Step 4</h4>
			<p>Setup your Zendesk <a href="jobs.php">tasks</a>.</p>
			
			<h4>Step 6</h4>
			<p>Sit back and wait for the jobs to come in!</p>
		</div>
	</div>
	
	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>

