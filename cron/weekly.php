<?php
$rootDir = dirname(__DIR__, 1);

include_once($rootDir . "/inc/database.php");
include_once($rootDir . "/inc/jobs.php");
include_once($rootDir . "/inc/logs.php");
include($rootDir . "/vendor/autoload.php");

$jobs = new jobs();
$jobs_weekly = $jobs->jobs_weekly();

foreach($jobs_weekly AS $job) {
	if ($job->status == "Enabled") {
		$job->create_zendesk_ticket();
	}
	else {
		$logRecord = new logs();
		$logRecord->description = "Didn't create job: " . $job->subject . " (" . $job->uid . ") because it was disabled.";
		$logRecord->type = "info";
		$logRecord->log_record();
	}
}
?>