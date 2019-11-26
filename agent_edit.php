<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

<?php
$team = new team();
$teamMember = $team->member($_GET['member']);

$jobs = new jobs();
$jobsAssigned = $jobs->jobs_assigned($teamMember->zendesk_id);

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
<body>
<?php include_once("views/navbar.php"); ?>
<div class="container">	
	<h4>Modify Team Member <i>(<?php echo $teamMember->firstname . " " . $teamMember->lastname; ?>)</i></h4>
	
	<div class="row">
		<div class="col-lg-6">
			<form action="agent_edit.php?member=<?php echo $teamMember->uid; ?>" method="post">
			<div class="form-group">
				<label for="inputFirstname">First Name</label>
				<input type="text" class="form-control" name="inputFirstname" placeholder="First Name" value="<?php echo $teamMember->firstname; ?>">
			</div>
			<div class="form-group">
				<label for="inputLastname">Last Name</label>
				<input type="text" class="form-control" name="inputLastname" placeholder="Last Name" value="<?php echo $teamMember->lastname; ?>">
			</div>
			<div class="form-group">
				<label for="inputEmail">Email Address</label>
				<input type="text" class="form-control" name="inputEmail" placeholder="Email Address" value="<?php echo $teamMember->email; ?>">
			</div>
			<div class="form-group">
				<label for="inputZendeskID">Zendesk ID</label>
				<input type="text" class="form-control" name="inputZendeskID" placeholder="Zendesk ID" value="<?php echo $teamMember->zendesk_id; ?>">
			</div>
			<div class="form-group">
				<label for="inputEnabled">Enabled/Disabled</label>
				<select class="form-control" name="inputEnabled">
					<option value="1" <?php if ($teamMember->enabled == "1") { echo " selected";}?>>Enabled</option>
					<option value="0" <?php if ($teamMember->enabled == "0") { echo " selected";}?>>Disabled</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary">Modify</button>
			<?php
			if (count($jobsAssigned) == 0) {
				echo "<button id=\"deleteJob\" type=\"submit\" class=\"btn btn-danger\">Delete</button>";
			} else {
				echo "<button id=\"deleteJob\" disabled type=\"submit\" class=\"btn btn-danger\">Delete</button>";
				echo "<p>* User cannot be deleted when there are jobs assigned to them</p>";
			}
			?>
			
			</form>
		</div>
		<div class="col-lg-6">
			<?php
			foreach($jobsAssigned AS $job) {
				echo $job->job_display();
			}
			?>
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
            location.href = 'agents.php?memberDelete=<?php echo $teamMember->uid; ?>';
        }
    });
});
</script>