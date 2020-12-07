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

$team = new team();
$teamMembers = $team->team_all_enabled();

?>
<body>
<?php include_once("views/navbar.php"); ?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Tickets</h1>
		<p class="lead">Daily, weekly, monthly and yearly tickets that are auto-scheduled to appear on Zendesk.</p>
	</div>

	<div class="pb-3 text-right">
		<a class="btn btn-primary" href="index.php?n=admin_meal" role="button" data-toggle="modal" data-target="#staticBackdrop">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
				<path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"/>
				<circle cx="3.5" cy="5.5" r=".5"/>
				<circle cx="3.5" cy="8" r=".5"/>
				<circle cx="3.5" cy="10.5" r=".5"/>
			</svg> Add new
		</a>
	</div>



	<h1>Daily</h1>
	<p>These tasks will appear on Zendesk at 00:00 every day (Monday - Friday).</p>
	<?php
	foreach($jobs_daily AS $job) {
		echo $job->job_display();
	}
	?>

	<h1>Weekly</h1>
	<p>These tasks will appear on Zendesk at 00:00 every Monday morning.</p>
	<?php
	foreach($jobs_weekly AS $job) {
		echo $job->job_display();
	}
	?>

	<h1>Monthly</h1>
	<p>These tasks will appear on Zendesk at 00:00 on the 1st of every month.</p>
	<?php
	foreach($jobs_monthly AS $job) {
		echo $job->job_display();
	}
	?>

	<h1>Yearly</h1>
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
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add New Scheduled Ticket</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
						foreach ($teamMembers AS $member) {
							$output  = "<option value=\"" . $member->zendesk_id . "\">" . $member->firstname . " " . $member->lastname . "</option>";

							echo $output;
						}
						?>
					</select>
				</div>
				<div class="mb-3">
					<label for="inputAssignTo" class="form-label">Auto-assign To Agent</label>
					<select class="form-select" id="inputAssignTo" name="inputAssignTo">
						<?php
						foreach ($teamMembers AS $member) {
							$output  = "<option value=\"" . $member->zendesk_id . "\">" . $member->firstname . " " . $member->lastname . "</option>";

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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
		</form>
  </div>
</div>
