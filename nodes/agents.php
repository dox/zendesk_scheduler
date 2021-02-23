<?php
$agentsClass = new agents();

if (!empty($_POST)) {
	$agentCreate = new agents();
	$agentCreate->firstname = $_POST['inputFirstname'];
	$agentCreate->lastname = $_POST['inputLastname'];
	$agentCreate->email = $_POST['inputEmail'];
	$agentCreate->zendesk_id = $_POST['inputZendeskID'];
	$agentCreate->enabled = $_POST['inputEnabled'];
	$agentCreate->create();
}

if (isset($_GET['import'])) {
	foreach ($agentsClass->getAgents("all") AS $agent) {
		$teamMembersZDidArray[] = $agent->zendesk_id;
	}

	foreach ($agentsClass->getZendeskAgents() AS $zdAgent) {
		if (in_array($zdAgent->id, $teamMembersZDidArray)) {
			// user already in local database
		} else {
			// user doesn't exist locally

			$agentCreate = new agents();
			$agentCreate->firstname = $zdAgent->name;
			//$agentCreate->lastname = $zdAgent->name;
			$agentCreate->email = $zdAgent->email;
			$agentCreate->zendesk_id = $zdAgent->id;
			$agentCreate->enabled = "1";
			$agentCreate->create();
		}
	}
}

if (isset($_GET['agentDelete'])) {
	$agentDelete = new agents();
	$agentDelete->uid = $_GET['agentDelete'];

	if ($agentDelete->delete()) {
	} else {
		echo "Something went wrong, please contact IT Support</div>";
	}
}
?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4"><svg width="1em" height="1em"><use xlink:href="inc/icons.svg#agents"/></svg> Agents</h1>
		<p class="lead">Agents from Zendesk that you can assign scheduled tickets too.</p>
	</div>

	<div class="pb-3 text-end">
		<a class="btn btn-warning" href="index.php?n=agents&import=true" role="button">
			<svg width="1em" height="1em"><use xlink:href="inc/icons.svg#zendesk"/></svg> Import Current Agents
		</a>
		<a class="btn btn-primary" href="index.php?n=admin_meal" role="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
			<svg width="1em" height="1em"><use xlink:href="inc/icons.svg#agents"/></svg> Add new
		</a>
	</div>

	<div class="row mb-3">
		<?php echo $agentsClass->displayMembers("enabled"); ?>
	</div>


	<h4>Previous Team Members</h4>
	<div class="row mb-3">
		<?php echo $agentsClass->displayMembers("disabled"); ?>
	</div>

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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
		</form>
  </div>
</div>
