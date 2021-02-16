<?php
require('./inc/autoload.php');

$jobs = new jobs();
$jobs_yearly = $jobs->jobs_yearly();

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
