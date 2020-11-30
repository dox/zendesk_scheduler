<?php
include_once("../inc/autoload.php");

$jobs = new jobs();
$jobs_daily = $jobs->jobs_daily();

foreach($jobs_daily AS $job) {
	if ($job->status == "Enabled") {
		$job->create_zendesk_ticket();
	} else {
		$logRecord = new logs();
		$logRecord->description = "Didn't create job: " . $job->subject . " (" . $job->uid . ") because it was disabled.";
		$logRecord->type = "info";
		$logRecord->log_record();
	}
}
?>
