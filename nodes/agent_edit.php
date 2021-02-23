<?php
$agentsClass = new agents();
$agent = $agentsClass->member($_GET['agentUID']);

$jobs = new jobs();
$jobsAssigned = $jobs->jobs_involved_with($agent->zendesk_id);

if (!empty($_POST)) {
	$agent->firstname = $_POST['inputFirstname'];
	$agent->lastname = $_POST['inputLastname'];
	$agent->email = $_POST['inputEmail'];
	$agent->zendesk_id = $_POST['inputZendeskID'];
	$agent->enabled = $_POST['inputEnabled'];

	if ($agent->member_update()) {
		$logRecord = new logs();
		$logRecord->description = "User details for " . $agent->firstname . " " . $agent->lastname . " modified";
		$logRecord->type = "admin";
		$logRecord->log_record();

		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Member Updated!</div>";
	} else {
		$logRecord = new logs();
		$logRecord->description = "Error trying to modify details for " . $agent->firstname . " " . $agent->lastname . " (User id: " . $teamMember->zendesk_id . ")";
		$logRecord->type = "error";
		$logRecord->log_record();

		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}

?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Agent Modify <small class="text-muted"><?php echo $agent->firstname . " " . $agent->lastname; ?></small></h1>
		<p class="lead"><?php echo $job->subject; ?></p>
	</div>

	<div class="pb-3 text-end">
		<?php
		if (count($jobsAssigned) == 0) {
			$disabled = "";
			$message = "";
			//echo "<button id=\"deleteJob\" type=\"submit\" class=\"btn btn-danger\">Delete</button>";
		} else {
			$disabled = " disabled ";
			$message = "<p>* User cannot be deleted when there are jobs assigned to/logged by them</p>";
			//echo "<button id=\"deleteJob\" disabled type=\"submit\" class=\"btn btn-danger\">Delete</button>";
			//echo "<p>* User cannot be deleted when there are jobs assigned to/logged by them</p>";
		}
		?>
		<a class="btn btn-danger <?php echo $disabled; ?>" id="deleteJob" href="#" role="button" onclick="deleteAgent();">
			<svg width="1em" height="1em"><use xlink:href="inc/icons.svg#delete"/></svg> Delete
		</a>
		<?php echo $message; ?>
	</div>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<div class="row">
		<div class="col-lg-6 mb-3">
			<div class="mb-3">
				<label for="inputFirstname" class="form-label">First Name</label>
				<input type="text" class="form-control" name="inputFirstname" value="<?php echo $agent->firstname; ?>">
			</div>
			<div class="mb-3">
				<label for="inputLastname" class="form-label">Last Name</label>
				<input type="text" class="form-control" name="inputLastname" value="<?php echo $agent->lastname; ?>">
			</div>
			<div class="mb-3">
				<label for="inputEmail" class="form-label">Email Address</label>
				<input type="email" class="form-control" name="inputEmail" value="<?php echo $agent->email; ?>">
			</div>
			<div class="mb-3">
				<label for="inputZendeskID" class="form-label">Zendesk ID</label>
				<input type="number" class="form-control" name="inputZendeskID" value="<?php echo $agent->zendesk_id; ?>">
			</div>
			<div class="mb-3">
				<label for="inputEnabled" class="form-label">User Account Status</label>
				<select class="form-select" id="inputEnabled" name="inputEnabled" aria-label="Default select example">
					<option value="1" <?php if ($agent->enabled == "1") { echo " selected";}?>>Enabled</option>
					<option value="0" <?php if ($agent->enabled == "0") { echo " selected";}?>>Disabled</option>
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
</div>

<script>
function deleteAgent() {
	if (window.confirm("Are you sure you want to run delete this job?  This action cannot be undone!")) {
			location.href = 'index.php?n=agents&memberDelete=<?php echo $agent->uid; ?>';
	}
}
</script>
