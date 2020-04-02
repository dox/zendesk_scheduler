<?php
$rootDir = dirname(__DIR__, 1);

include_once($rootDir . "/inc/database.php");
include_once($rootDir . "/inc/jobs.php");
include_once($rootDir . "/inc/logs.php");
include($rootDir . "/vendor/autoload.php");

$jobs = new jobs();
$jobs_daily = $jobs->jobs_daily();
$jobs_yearly = $jobs->jobs_yearly();

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

foreach($jobs_yearly AS $job) {
	$freqArray = explode(",", strtoupper($job->frequency2));
	
	foreach ($freqArray AS $dateToRun) {
		if ($dateToRun == strtoupper(date('M-d'))) {
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
	}
	
}
?>