<?php
$team = new team();
$teamMember = $team->member($_GET['member']);

$jobs = new jobs();
$jobsAssigned = $jobs->jobs_involved_with($teamMember->zendesk_id);

if (!empty($_POST)) {
	$teamMember->firstname = $_POST['inputFirstname'];
	$teamMember->lastname = $_POST['inputLastname'];
	$teamMember->email = $_POST['inputEmail'];
	$teamMember->zendesk_id = $_POST['inputZendeskID'];
	$teamMember->enabled = $_POST['inputEnabled'];

	if ($teamMember->member_update()) {
		$logRecord = new logs();
		$logRecord->description = "User details for " . $teamMember->firstname . " " . $teamMember->lastname . " modified";
		$logRecord->type = "admin";
		$logRecord->log_record();

		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Member Updated!</div>";
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error trying to modify details for " . $teamMember->firstname . " " . $teamMember->lastname . " (User id: " . $teamMember->zendesk_id . ")";
		$logRecord->type = "error";
		$logRecord->log_record();

		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}

?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Agent Modify <small class="text-muted"><?php echo $teamMember->firstname . " " . $teamMember->lastname; ?></small></h1>
		<p class="lead"><?php echo $job->subject; ?></p>
	</div>

	<div class="pb-3 text-right">
		<?php
		if (count($jobsAssigned) == 0) {
			$disabled = "";
			$message = "";
			//echo "<button id=\"deleteJob\" type=\"submit\" class=\"btn btn-danger\">Delete</button>";
		} else {
			$disabled = " disabled ";
			$message = "<p>* User cannot be deleted when there are jobs assigned to/logged by them</op";
			//echo "<button id=\"deleteJob\" disabled type=\"submit\" class=\"btn btn-danger\">Delete</button>";
			//echo "<p>* User cannot be deleted when there are jobs assigned to/logged by them</p>";
		}
		?>
		<a class="btn btn-danger <?php echo $disabled; ?>" id="deleteJob" href="#" role="button" onclick="deleteAgent();">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
				<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
			</svg> Delete
		</a>
		<?php echo $message; ?>
	</div>
</div>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<div class="row">
	<div class="col-lg-6">
		<div class="mb-3">
			<label for="inputFirstname" class="form-label">First Name</label>
			<input type="text" class="form-control" name="inputFirstname" value="<?php echo $teamMember->firstname; ?>">
		</div>
		<div class="mb-3">
			<label for="inputLastname" class="form-label">Last Name</label>
			<input type="text" class="form-control" name="inputLastname" value="<?php echo $teamMember->lastname; ?>">
		</div>
		<div class="mb-3">
			<label for="inputEmail" class="form-label">Email Address</label>
			<input type="email" class="form-control" name="inputEmail" value="<?php echo $teamMember->email; ?>">
		</div>
		<div class="mb-3">
			<label for="inputZendeskID" class="form-label">Zendesk ID</label>
			<input type="number" class="form-control" name="inputZendeskID" value="<?php echo $teamMember->zendesk_id; ?>">
		</div>
		<div class="mb-3">
			<label for="inputEnabled" class="form-label">User Account Status</label>
			<select class="form-select" id="inputEnabled" name="inputEnabled" aria-label="Default select example">
				<option value="1" <?php if ($teamMember->enabled == "1") { echo " selected";}?>>Enabled</option>
				<option value="0" <?php if ($teamMember->enabled == "0") { echo " selected";}?>>Disabled</option>
			</select>
		</div>
		<div class="d-grid gap-2">
			<button type="submit" class="btn btn-primary">Modify</button>
		</div>
	</div>
	<div class="col-lg-6">
		<h4>Jobs assigned to/logged by:</h4>
		<?php
		foreach($jobsAssigned AS $job) {
			echo $job->job_display();
		}
		?>
	</div>
</div>
</form>

<script>
function deleteAgent() {
	if (window.confirm("Are you sure you want to run delete this job?  This action cannot be undone!")) {
			location.href = 'index.php?n=agents&memberDelete=<?php echo $teamMember->uid; ?>';
	}
}
</script>
