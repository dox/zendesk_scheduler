<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

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
	
	if ($job->job_update()) {
		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Job Updated!</div>";
	} else {
		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}



?>
<body>
<?php include_once("views/navbar.php"); ?>
<div class="container">	
	
	<div class="row marketing">
		<div class="col-lg-12">
		<h4>Modify Job <i>(UID: <?php echo $job->uid; ?>)</i></h4>
		<form action="job_edit.php?job=<?php echo $job->uid; ?>" method="post">
		<div class="form-group">
			<label for="inputSubject">Subject</label>
			<input type="text" class="form-control" name="inputSubject" placeholder="Subject" value="<?php echo $job->subject; ?>">
		</div>
		<div class="form-group">
			<label for="inputBody">Message Body</label>
			<textarea class="form-control" rows="7" name="inputBody" placeholder="Message"><?php echo $job->body; ?></textarea>
		</div>
		<div class="form-group">
			<label for="inputType">Type</label>
			<select class="form-control" name="inputType">
				<option value="Question" <?php if ($job->type == "Question") { echo " selected";}?>>Question</option>
				<option value="Problem" <?php if ($job->type == "Problem") { echo " selected";}?>>Problem</option>
				<option value="Task" <?php if ($job->type == "Task") { echo " selected";}?>>Task</option>
			</select>
		</div>
		<div class="form-group">
			<label for="inputPriority">Priority</label>
			<select class="form-control" name="inputPriority">
				<option value="Low" <?php if ($job->priority == "Low") { echo " selected";}?>>Low</option>
				<option value="Normal" <?php if ($job->priority == "Normal") { echo " selected";}?>>Normal</option>
				<option value="High" <?php if ($job->priority == "High") { echo " selected";}?>>High</option>
				<option value="Urgent" <?php if ($job->priority == "Urgent") { echo " selected";}?>>Urgent</option>
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
			<label for="inputAssignTo">Logged By</label>
		    <select class="form-control" id="inputLoggedBy" name="inputLoggedBy">
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
		<div class="form-group">
			<label for="inputCC">CC (email addresses)</label>
			<input type="text" class="form-control" id="inputCC" name="inputCC" placeholder="CC (email addresses)" value="<?php echo $job->cc; ?>">
		</div>
		<div class="form-group">
			<label for="inputTags">Tags</label>
			<input type="text" class="form-control" name="inputTags" placeholder="Tags" value="<?php echo $job->tags; ?>">
		</div>
		<div class="form-group">
			<label for="inputFrequency">Frequency</label>
			<select class="form-control" name="inputFrequency">
				<option value="Daily" <?php if ($job->frequency == "Daily") { echo " selected";}?>>Daily</option>
				<option value="Weekly" <?php if ($job->frequency == "Weekly") { echo " selected";}?>>Weekly</option>
				<option value="Monthly" <?php if ($job->frequency == "Monthly") { echo " selected";}?>>Monthly</option>
				<option value="Yearly" <?php if ($job->frequency == "Yearly") { echo " selected";}?>>Yearly</option>
			</select>
		</div>
		<div id="Frequency2" class="form-group" <?php if ($job->frequency <> 'Yearly') { echo 'hidden'; } ?>>
			<label for="inputFrequency2">Frequency2</label>
			<input type="text" class="form-control" name="inputFrequency2" placeholder="Frequency2" value="<?php echo strtoupper($job->frequency2); ?>">
			<small id="inputFrequency2Help" class="form-text text-muted">The day of the year you want this task to run, written in the format '<?php echo strtoupper(date('M-d'));?>' (with leading zeros).<br />Specify multiple dates by using a comma to separate them (no spaces!) like: '<?php echo strtoupper(date('M-d')) ."," . strtoupper(date('M-d',strtotime(' +1 day')));?>'</small>
		</div>
		<button type="submit" class="btn btn-primary">Modify</button>
		<button id="deleteJob" type="submit" class="btn btn-danger float-right">Delete</button>
		<button id="runJob" type="submit" class="btn btn-warning">Run Now</button>
</form>
		</div>
	</div>
	
	<?php include_once("views/footer.php"); ?>
	
	</div> <!-- /container -->
</body>
</html>

<script>


$(function() {
    $('#deleteJob').click(function(e) {
        e.preventDefault();
        if (window.confirm("Are you sure?")) {
            location.href = 'jobs.php?jobDelete=<?php echo $job->uid; ?>';
        }
    });
});

$(function() {
    $('#runJob').click(function(e) {
        e.preventDefault();
        if (window.confirm("Are you sure you want to run this job now?")) {
            location.href = 'jobs.php?jobRun=<?php echo $job->uid; ?>';
        }
    });
});

$('select[name="inputFrequency"]').change(function(){
	if ($(this).val() == "Yearly"){
		$('#Frequency2').prop('hidden', false);
	} else {
		$('#Frequency2').prop('hidden', true);
	}  
})
</script>