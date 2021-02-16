<?php
require('./inc/autoload.php');

$jobs = new jobs();
$jobs_oneoff = $jobs->jobs_oneoff();

foreach($jobs_oneoff AS $job) {
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
