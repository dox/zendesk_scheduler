<?php
if (!empty($_POST)) {
	$jobCreate = new jobs();
	$jobCreate->subject = $_POST['inputSubject'];
	$jobCreate->body = $_POST['inputBody'];
	$jobCreate->type = $_POST['inputType'];
	$jobCreate->priority = $_POST['inputPriority'];
	$jobCreate->tags = $_POST['inputTags'];
	$jobCreate->frequency = $_POST['inputFrequency'];
	$jobCreate->frequency2 = str_replace(' ', '', strtoupper($_POST['inputFrequency2']));
	$jobCreate->assign_to = $_POST['inputAssignTo'];
	$jobCreate->logged_by = $_POST['inputLoggedBy'];
	$jobCreate->cc = $_POST['inputCC'];
	$jobCreate->status = 'Enabled';
	$jobCreate->job_create();
}

if (isset($_GET['jobDelete'])) {
	$jobsDelete = new jobs();
	$jobsDelete->uid = $_GET['jobDelete'];

	if ($jobsDelete->job_delete()) {
		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Job Deleted</div>";
	} else {
		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}

if (isset($_GET['jobRun'])) {
	$jobRun = new jobs();
	$jobRun = $jobRun->job($_GET['jobRun']);


	if ($jobRun->create_zendesk_ticket()) {
		echo "<div class=\"alert alert-success\" role=\"alert\">Succesfully submitted ticket to Zendesk via API</div>";
	} else {
		echo "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}

$jobs = new jobs();
$jobs_daily = $jobs->jobs_daily();
$jobs_weekly = $jobs->jobs_weekly();
$jobs_monthly = $jobs->jobs_monthly();
$jobs_yearly = $jobs->jobs_yearly();

$agentsClass = new agents();
?>

<div class="container">
	<?php
	$title = "<svg width=\"1em\" height=\"1em\"><use xlink:href=\"inc/icons.svg#tickets\"/></svg> Tickets";
	$subtitle = "Daily, weekly, monthly and yearly tickets that are auto-scheduled to appear on Zendesk.";
	$icons[] = array("class" => "btn-primary", "name" => "<svg width=\"1em\" height=\"1em\"><use xlink:href=\"inc/icons.svg#tickets\"/></svg> Add Ticket", "value" => "data-bs-toggle=\"modal\" data-bs-target=\"#ticketAddModal\"");

	echo makeTitle($title, $subtitle, $icons);
	?>

	<h1>Daily</h1>
	<p>These tasks will appear on Zendesk at 00:00 every day (Monday - Friday).</p>
	<?php
	foreach($jobs_daily AS $job) {
		echo $job->job_display();
	}
	?>

	<h1 class="mt-3">Weekly</h1>
	<p>These tasks will appear on Zendesk at 00:00 every Monday morning.</p>
	<?php
	foreach($jobs_weekly AS $job) {
		echo $job->job_display();
	}
	?>

	<h1 class="mt-3">Monthly</h1>
	<p>These tasks will appear on Zendesk at 00:00 on the 1st of every month.</p>
	<?php
	foreach($jobs_monthly AS $job) {
		echo $job->job_display();
	}
	?>

	<h1 class="mt-3">Yearly</h1>
	<p>These tasks will appear on Zendesk at 00:00 once every year on the date(s) specified.</p>
	<?php
	foreach($jobs_yearly AS $job) {
		echo $job->job_display();
	}
	?>
</div>

<script>
function toggleFrequency2() {
	d = document.getElementById("inputFrequency").value;

	if (d == 'Yearly'){
		document.getElementById("inputFrequency2Div").removeAttribute("hidden");
	} else {
		document.getElementById("inputFrequency2Div").setAttribute("hidden", true);
	}
}
</script>



<!-- Modal -->
<div class="modal fade" id="ticketAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add New Scheduled Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<div class="mb-3">
					<label for="inputSubject" class="form-label">Ticket Subject</label>
					<input type="text" class="form-control" id="inputSubject" name="inputSubject">
				</div>
				<div class="mb-3">
					<label for="inputBody" class="form-label">Ticket Body</label>
					<textarea class="form-control" rows="3" id="inputBody" name="inputBody"></textarea>
				</div>
				<div class="mb-3">
					<label for="inputType" class="form-label">Ticket Type</label>
					<select class="form-select" id="inputType" name="inputType">
						<option value="Question">Question</option>
						<option value="Problem">Problem</option>
						<option value="Task">Task</option>
					</select>
				</div>
				<div class="mb-3">
					<label for="inputPriority" class="form-label">Ticket Priority</label>
					<select class="form-select" id="inputPriority" name="inputPriority">
						<option value="Low">Low</option>
						<option value="Normal">Normal</option>
						<option value="High">High</option>
						<option value="Urgent">Urgent</option>
					</select>
				</div>
				<div class="mb-3">
					<label for="inputLoggedBy" class="form-label">Ticket Logged By</label>
					<select class="form-select" id="inputLoggedBy" name="inputLoggedBy">
						<?php
						foreach ($agentsClass->getAgents("enabled") AS $agent) {
							$output  = "<option value=\"" . $agent->zendesk_id . "\">" . $agent->firstname . " " . $agent->lastname . "</option>";

							echo $output;
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label for="inputAssignTo" class="form-label">Auto-assign To Agent</label>
					<select class="form-select" id="inputAssignTo" name="inputAssignTo">
						<?php
						foreach ($agentsClass->getAgents("enabled") AS $agent) {
							$output  = "<option value=\"" . $agent->zendesk_id . "\">" . $agent->firstname . " " . $agent->lastname . "</option>";

							echo $output;
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label for="inputCC" class="form-label">Ticket CC</label>
					<input type="text" class="form-control" id="inputCC" name="inputCC" aria-describedby="inputCCHelp">
					<div id="inputCCHelp" class="form-text">Comma-seperated list of email addresses to CC into this ticket.</div>
				</div>
				<div class="mb-3">
					<label for="inputTags" class="form-label">Ticket Tags</label>
					<input type="text" class="form-control" id="inputTags" name="inputTags" aria-describedby="inputTagsHelp">
					<div id="inputTagsHelp" class="form-text">Comma-seperated list of tags to include into this ticket.</div>
				</div>
				<div class="mb-3">
					<label for="inputFrequency" class="form-label">Ticket Frequency</label>
					<select class="form-select" id="inputFrequency" name="inputFrequency" onchange="toggleFrequency2()">
						<option value="Daily">Daily</option>
						<option value="Weekly">Weekly</option>
						<option value="Monthly">Monthly</option>
						<option value="Yearly">Yearly</option>
					</select>
				</div>
				<div class="mb-3" id="inputFrequency2Div" hidden>
					<label for="inputFrequency2" class="form-label">Yearly Frequency</label>
					<input type="text" class="form-control" id="inputFrequency2" name="inputFrequency2" aria-describedby="inputFrequency2Help">
					<div id="inputFrequency2Help" class="form-text">The day of the year you want this task to run, written in the format '<?php echo strtoupper(date('M-d'));?>' (with leading zeros).<br />Specify multiple dates by using a comma to separate them (no spaces!) like: '<?php echo strtoupper(date('M-d')) ."," . strtoupper(date('M-d',strtotime(' +1 day')));?>'.</div>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><svg width="1em" height="1em"><use xlink:href="inc/icons.svg#tickets"/></svg> Add Ticket</button>
      </div>
    </div>
		</form>
  </div>
</div>
