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
			<svg width="1em" height="1em"><use xlink:href="inc/icons.svg#run-now"></svg> Run Now
		</a>
		<a class="btn btn-danger" id="deleteJob" href="#" role="button" onclick="deleteJob();">
			<svg width="1em" height="1em"><use xlink:href="inc/icons.svg#delete"/></svg> Delete
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
	<div class="row g-12">
		<div class="col-md-6 mb-3">
			<label for="inputType" class="form-label">Ticket Type</label>
			<select class="form-select" id="inputType" name="inputType">
				<option value="Question" <?php if ($job->type == "Question") { echo " selected";}?>>Question</option>
				<option value="Problem" <?php if ($job->type == "Problem") { echo " selected";}?>>Problem</option>
				<option value="Task"<?php if ($job->type == "Task") { echo " selected";}?> >Task</option>
			</select>
		</div>
		<div class="col-md-6 mb-3">
			<label for="inputPriority" class="form-label">Ticket Priority</label>
			<select class="form-select" id="inputPriority" name="inputPriority">
				<option value="Low" <?php if ($job->priority == "Low") { echo " selected";}?>>Low</option>
				<option value="Normal" <?php if ($job->priority == "Normal") { echo " selected";}?>>Normal</option>
				<option value="High" <?php if ($job->priority == "High") { echo " selected";}?>>High</option>
				<option value="Urgent" <?php if ($job->priority == "Urgent") { echo " selected";}?>>Urgent</option>
			</select>
		</div>
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
	<div class="d-grid gap-2">
		<button type="submit" class="btn btn-primary">Save</button>
	</div>
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
