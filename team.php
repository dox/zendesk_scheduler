<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php"); ?>

<?php
if (!empty($_POST)) {
	$jobCreate = new team();
	$jobCreate->firstname = $_POST['inputFirstname'];
	$jobCreate->lastname = $_POST['inputLastname'];
	$jobCreate->email = $_POST['inputEmail'];
	$jobCreate->zendesk_id = $_POST['inputZendeskID'];
	$jobCreate->enabled = $_POST['inputEnabled'];
	$jobCreate->team_create();
}

if (isset($_GET['memberDelete'])) {
	$teamDelete = new team();
	$teamDelete->uid = $_GET['memberDelete'];
	
	if ($teamDelete->member_delete()) {
		$messages[] = "<div class=\"alert alert-success\" role=\"alert\">Team Member Deleted</div>";
	} else {
		$messages[] = "<div class=\"alert alert-danger\" role=\"alert\">Something went wrong, please contact IT Support</div>";
	}
}

$team = new team();
$teamMembersEnabled = $team->team_all_enabled();
$teamMembersDisabled = $team->team_all_disabled();

?>
<body>
<?php include_once("views/navbar.php"); ?>
<div class="container">	
	
	<button type="button" class="btn btn-primary btn-lg float-right" id="showAddTeam">Add New</button>
	<div id="AddTeam" hidden>
		<h4>Add New Team Member</h4>
	    <form action="team.php" method="post">
	    <div class="form-group">
	    	<label for="inputFirstname">First Name</label>
	    	<input type="text" class="form-control" id="inputFirstname" name="inputFirstname" placeholder="First Name">
	    </div>
	    <div class="form-group">
	    	<label for="inputLastname">Last Name</label>
	    	<input type="text" class="form-control" id="inputLastname" name="inputLastname" placeholder="Last Name">
	    </div>
	    <div class="form-group">
	    	<label for="inputEmail">Email Address</label>
	    	<input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email Address">
	    </div>
	    <div class="form-group">
	    	<label for="inputZendeskID">Zendesk ID (required)</label>
	    	<input type="text" class="form-control" id="inputZendeskID" name="inputZendeskID" placeholder="Zendesk ID">
	    </div>
	    <div class="form-group">
	    	<label for="inputEnabled">Enabled/Disabled</label>
	    	<select class="form-control" id="inputEnabled" name="inputEnabled">
	    		<option value="1">Enabled</option>
	    		<option value="0">Disabled</option>
	    	</select>
	    </div>
	    <button type="submit" class="btn btn-primary">Submit</button>
	    </form>
	</div><!-- end hiding box -->
	
	<h4>Team Members</h4>
	<?php
	foreach($teamMembersEnabled AS $member) {
		echo $member->member_display();
	}
	?>
	
	<h4>Previous Team Members</h4>
	<?php
	foreach($teamMembersDisabled AS $member) {
		echo $member->member_display();
	}
	?>
	
	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>

<script>
$('#showAddTeam').on('click', function (e) {
    var bool = $('#AddTeam').prop('hidden');
    $('#AddTeam').prop('hidden', ! bool);
})
</script>
