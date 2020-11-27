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

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Agents</h1>
		<p class="lead">Agents from your Zendesk that you want to assign scheduled tickets.</p>
	</div>

	<div class="pb-3 text-right">
		<a class="btn btn-primary" href="index.php?n=admin_meal" role="button" data-toggle="modal" data-target="#staticBackdrop">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" d="M8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10zM13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
			</svg> Add new
		</a>
	</div>

	<div class="row">
		<?php
		foreach($teamMembersEnabled AS $member) {
			echo $member->member_display();
		}
		?>
	</div>

	<h4>Previous Team Members</h4>
	<div class="row">
		<?php
		foreach($teamMembersDisabled AS $member) {
			echo $member->member_display();
		}
		?>
	</div>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add New Agent</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
				<div class="mb-3">
					<label for="inputFirstname" class="form-label">First Name</label>
					<input type="text" class="form-control" id="inputFirstname" name="inputFirstname">
				</div>
				<div class="mb-3">
					<label for="inputLastname" class="form-label">Last Name</label>
					<input type="text" class="form-control" id="inputLastname" name="inputLastname">
				</div>
				<div class="mb-3">
					<label for="inputEmail" class="form-label">Email Address</label>
					<input type="email" class="form-control" id="inputEmail" name="inputEmail">
				</div>
				<div class="mb-3">
					<label for="inputZendeskID" class="form-label">Zendesk ID (required)</label>
					<input type="number" class="form-control" id="inputZendeskID" name="inputZendeskID" aria-describedby="inputZendeskIDHelp">
					<div id="inputZendeskIDHelp" class="form-text">This is the ID in the Zendesk URL of the agent</div>
				</div>
				<div class="mb-3">
					<label for="inputEnabled" class="form-label">User Account Status</label>
					<select class="form-select" id="inputEnabled" name="inputEnabled" aria-label="Default select example">
		    		<option value="1">Enabled</option>
		    		<option value="0">Disabled</option>
		    	</select>
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
