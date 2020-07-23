<?php
$currentPage = basename($_SERVER["SCRIPT_FILENAME"], '.php');
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
<div class="container">
	<a class="navbar-brand" href="#">Zendesk Scheduler</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item <?php if($currentPage == "index") { echo "active";}?>"><a class="nav-link" href="index.php">Home</a></li>
			<li class="nav-item <?php if($currentPage == "jobs" || $currentPage == "job_edit") { echo "active";}?>"><a class="nav-link" href="jobs.php">Jobs</a></li>
			<li class="nav-item <?php if($currentPage == "agents" || $currentPage == "agent_edit") { echo "active";}?>"><a class="nav-link" href="agents.php">Agents</a></li>
			<li class="nav-item <?php if($currentPage == "logs") { echo "active";}?>"><a class="nav-link" href="logs.php">Logs</a></li>
		</ul>
	</div>
</div>
</nav>

<div class="container">
<?php
foreach ($messages AS $message) {
	echo $message;
}
?>
</div>

<style>
	body {
    padding-top: 65px;
}
</style>
