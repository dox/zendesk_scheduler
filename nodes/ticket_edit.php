<?php
$jobs = new jobs();
$job = $jobs->job($_GET['job']);

$team = new team();
$teamMembers = $team->team_all_enabled();

if (!empty($_POST)) {
	$job->subject = $_POST['inputSubject'];
	$job->body = $_POST['inputBody'];
	$job->type = $_POST['inputType'];
	$job->priority = $_POST['inputPriority'];
	$job->tags = $_POST['inputTags'];
	$job->frequency = $_POST['inputFrequency'];
	$job->frequency2 = str_replace(' ', '', strtoupper($_POST['inputFrequency2']));
	$job->assign_to = $_POST['inputAssignTo'];
	$job->logged_by = $_POST['inputLoggedBy'];
	$job->cc = $_POST['inputCC'];
	$job->status = $_POST['inputStatus'];

	if ($job->job_update()) {
		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Job Updated!</div>";
	} else {
		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}
?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Ticket Modify <small class="text-muted">[uid:<?php echo $job->uid; ?>]</small></h1>
		<p class="lead"><?php echo $job->subject; ?></p>
	</div>

	<div class="pb-3 text-right">
		<a class="btn btn-warning" id="runJob" href="#" role="button" onclick="runJob();">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-repeat" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
				<path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
			</svg> Run Now
		</a>
		<a class="btn btn-danger" id="deleteJob" href="#" role="button" onclick="deleteJob();">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
				<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
			</svg> Delete
		</a>
	</div>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<div class="mb-3">
		<label for="inputSubject" class="form-label">Ticket Subject</label>
		<input type="text" class="form-control" id="inputSubject" name="inputSubject" value="<?php echo $job->subject; ?>">
	</div>
	<div class="mb-3">
		<label for="inputBody" class="form-label">Ticket Body</label>
		<textarea class="form-control" rows="3" id="inputBody" name="inputBody"><?php echo $job->body; ?></textarea>
	</div>
	<div class="mb-3">
		<label for="inputType" class="form-label">Ticket Type</label>
		<select class="form-select" id="inputType" name="inputType">
			<option value="Question" <?php if ($job->type == "Question") { echo " selected";}?>>Question</option>
			<option value="Problem" <?php if ($job->type == "Problem") { echo " selected";}?>>Problem</option>
			<option value="Task"<?php if ($job->type == "Task") { echo " selected";}?> >Task</option>
		</select>
	</div>
	<div class="mb-3">
		<label for="inputPriority" class="form-label">Ticket Priority</label>
		<select class="form-select" id="inputPriority" name="inputPriority">
			<option value="Low" <?php if ($job->priority == "Low") { echo " selected";}?>>Low</option>
			<option value="Normal" <?php if ($job->priority == "Normal") { echo " selected";}?>>Normal</option>
			<option value="High" <?php if ($job->priority == "High") { echo " selected";}?>>High</option>
			<option value="Urgent" <?php if ($job->priority == "Urgent") { echo " selected";}?>>Urgent</option>
		</select>
	</div>
	<div class="mb-3">
		<label for="inputLoggedBy" class="form-label">Ticket Logged By</label>
		<select class="form-select" id="inputLoggedBy" name="inputLoggedBy">
			<?php
			foreach ($teamMembers AS $member) {
				$output  = "<option value=\"" . $member->zendesk_id . "\"";
				if ($job->logged_by == $member->zendesk_id) {
					$output .= " selected";
				}
				$output .= ">" . $member->firstname . " " . $member->lastname . "</option>";

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
				$output  = "<option value=\"" . $member->zendesk_id . "\"";
				if ($job->assign_to == $member->zendesk_id) {
					$output .= " selected";
				}
				$output .= ">" . $member->firstname . " " . $member->lastname . "</option>";

				echo $output;
			}
			?>
		</select>
	</div>
	<div class="mb-3">
		<label for="inputCC" class="form-label">Ticket CC</label>
		<input type="text" class="form-control" id="inputCC" name="inputCC" aria-describedby="inputCCHelp" value="<?php echo $job->cc; ?>">
		<div id="inputCCHelp" class="form-text">Comma-seperated list of email addresses to CC into this ticket.</div>
	</div>
	<div class="mb-3">
		<label for="inputTags" class="form-label">Ticket Tags</label>
		<input type="text" class="form-control" id="inputTags" name="inputTags" aria-describedby="inputTagsHelp" value="<?php echo $job->tags; ?>">
		<div id="inputTagsHelp" class="form-text">Comma-seperated list of tags to include into this ticket.</div>
	</div>
	<div class="mb-3">
		<label for="inputFrequency" class="form-label">Ticket Frequency</label>
		<select class="form-select" id="inputFrequency" name="inputFrequency" onchange="toggleFrequency2()">
			<option value="Daily" <?php if ($job->frequency == "Daily") { echo " selected";}?>>Daily</option>
			<option value="Weekly" <?php if ($job->frequency == "Weekly") { echo " selected";}?>>Weekly</option>
			<option value="Monthly" <?php if ($job->frequency == "Monthly") { echo " selected";}?>>Monthly</option>
			<option value="Yearly" <?php if ($job->frequency == "Yearly") { echo " selected";}?>>Yearly</option>
		</select>
	</div>
	<div class="mb-3" id="inputFrequency2Div" <?php if ($job->frequency <> 'Yearly') { echo 'hidden'; } ?>>
		<label for="inputFrequency2" class="form-label">Yearly Frequency</label>
		<input type="text" class="form-control" id="inputFrequency2" name="inputFrequency2" aria-describedby="inputFrequency2Help" value="<?php echo strtoupper($job->frequency2); ?>">
		<div id="inputFrequency2Help" class="form-text">The day of the year you want this task to run, written in the format '<?php echo strtoupper(date('M-d'));?>' (with leading zeros).<br />Specify multiple dates by using a comma to separate them (no spaces!) like: '<?php echo strtoupper(date('M-d')) ."," . strtoupper(date('M-d',strtotime(' +1 day')));?>'.</div>
	</div>
	<div class="mb-3">
		<label for="inputStatus" class="form-label">Ticket Status</label>
		<select class="form-select" id="inputStatus" name="inputStatus">
			<option value="Enabled" <?php if ($job->status == "Enabled") { echo " selected";}?>>Enabled</option>
			<option value="Disabled" <?php if ($job->status == "Disabled") { echo " selected";}?>>Disabled</option>
		</select>
	</div>
	<button type="submit" class="btn btn-primary">Modify</button>
	</form>
</div>


<script>
function deleteJob() {
	if (window.confirm("Are you sure you want to run delete this job?  This action cannot be undone!")) {
			location.href = 'index.php?n=tickets&jobDelete=<?php echo $job->uid; ?>';
	}
}

function runJob() {
	if (window.confirm("Are you sure you want to run this job now?")) {
			location.href = 'index.php?n=tickets&jobRun=<?php echo $job->uid; ?>';
	}
}

function toggleFrequency2() {
	d = document.getElementById("inputFrequency").value;

	if (d == 'Yearly'){
		document.getElementById("inputFrequency2Div").removeAttribute("hidden");
	} else {
		document.getElementById("inputFrequency2Div").setAttribute("hidden", true);
	}
}
</script>
