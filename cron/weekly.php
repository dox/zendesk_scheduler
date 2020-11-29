<?php
include_once("../inc/autoload.php");

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
