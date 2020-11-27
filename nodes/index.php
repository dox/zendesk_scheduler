<section class="py-5 text-center container">
	<div class="row py-lg-5">
		<div class="col-lg-6 col-md-8 mx-auto">
			<h1 class="font-weight-light">Zendesk Scheduler</h1>
			<p class="lead text-muted">A simple web-based utility to create and manage reoccurring Zendesk ticket creation</p>
			<p>
				<a href="<?php echo zd_url; ?>" class="btn btn-primary my-2">View Zendesk</a>
				<!--<a href="#" class="btn btn-secondary my-2">Secondary action</a>-->
			</p>
		</div>
	</div>
</section>

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
