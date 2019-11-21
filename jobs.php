
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

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
		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Manually run job</div>";
	} else {
		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
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
	
	<button type="button" class="btn btn-primary btn-lg float-right" id="showAddJob">Add New</button>
	<div id="AddJob" hidden><!-- hiding box -->
		<h4>Add New Job</h4>
		<form action="jobs.php" method="post">
		<div class="form-group">
			<label for="inputSubject">Subject</label>
			<input type="text" class="form-control" id="inputSubject" name="inputSubject" placeholder="Subject">
		</div>
		<div class="form-group">
			<label for="inputBody">Message Body</label>
			<textarea class="form-control" rows="3" id="inputBody" name="inputBody" placeholder="Message"></textarea>
		</div>
		<div class="form-group">
			<label for="inputType">Type</label>
			<select class="form-control" id="inputType" name="inputType">
				<option value="Question">Question</option>
				<option value="Problem">Problem</option>
				<option value="Task">Task</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputPriority">Priority</label>
			<select class="form-control" id="inputPriority" name="inputPriority">
				<option value="Low">Low</option>
				<option value="Normal">Normal</option>
				<option value="High">High</option>
				<option value="Urgent">Urgent</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputAssignTo">Auto Assign To</label>
			<select class="form-control" id="inputAssignTo" name="inputAssignTo">
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
		<div class="form-group">
			<label for="inputLoggedBy">Logged By</label>
			<select class="form-control" id="inputLoggedBy" name="inputLoggedBy">
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
		<div class="form-group">
			<label for="inputCC">CC (email addresses)</label>
			<input type="text" class="form-control" id="inputCC" name="inputCC" placeholder="CC (email addresses)">
		</div>
		<div class="form-group">
			<label for="inputTags">Tags</label>
			<input type="text" class="form-control" id="inputTags" name="inputTags">
		</div>
		<div class="form-group">
			<label for="inputFrequency">Frequency</label>
			<select class="form-control" id="inputFrequency" name="inputFrequency">
				<option value="Daily">Daily</option>
				<option value="Weekly">Weekly</option>
				<option value="Monthly">Monthly</option>
				<option value="Yearly">Yearly</option>
			</select>
		</div>
		<div id="Frequency2" class="form-group" hidden>
			<label for="inputFrequency2">Frequency2</label>
			<input type="text" class="form-control" id="inputFrequency2" name="inputFrequency2">
			<small id="inputFrequency2Help" class="form-text text-muted">The day of the year you want this task to run, written in the format '<?php echo strtoupper(date('M-d'));?>' (with leading zeros).<br />Specify multiple dates by using a comma to separate them (no spaces!) like: '<?php echo strtoupper(date('M-d')) ."," . strtoupper(date('M-d',strtotime(' +1 day')));?>'</small>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div><!-- end hiding box -->
	
	<h4>Daily</h4>
	<p>These tasks will appear on Zendesk at 00:00 every day (Monday - Friday).</p>
	<?php
	foreach($jobs_daily AS $job) {
		echo $job->job_display();
	}
	?>
	
	<h4>Weekly</h4>
	<p>These tasks will appear on Zendesk at 00:00 every Monday morning.</p>
	<?php
	foreach($jobs_weekly AS $job) {
		echo $job->job_display();
	}
	?>
	
	<h4>Monthly</h4>
	<p>These tasks will appear on Zendesk at 00:00 on the 1st of every month.</p>
	<?php
	foreach($jobs_monthly AS $job) {
		echo $job->job_display();
	}
	?>
	
	<h4>Yearly</h4>
	<p>These tasks will appear on Zendesk at 00:00 once every year on the date(s) specified.</p>
	<?php
	foreach($jobs_yearly AS $job) {
		echo "<small class=\"form-text text-muted\">" . strtoupper($job->frequency2) . "</small>";
		echo $job->job_display();
	}
	?>
	
	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>

<script>
$('#showAddJob').on('click', function (e) {
    var bool = $('#AddJob').prop('hidden');
    $('#AddJob').prop('hidden', ! bool);
})



$('select[name="inputFrequency"]').change(function(){
	if ($(this).val() == "Yearly"){
		$('#Frequency2').prop('hidden', false);
	} else {
		$('#Frequency2').prop('hidden', true);
	}  
})
</script>
