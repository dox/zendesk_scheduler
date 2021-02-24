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
	<?php
	$title = "<svg width=\"1em\" height=\"1em\"><use xlink:href=\"inc/icons.svg#agents\"/></svg> Agents";
	$subtitle = "Agents impored from Zendesk that can be assigned scheduled tickets.";
	$icons[] = array("class" => "btn-warning", "name" => "<svg width=\"1em\" height=\"1em\"><use xlink:href=\"inc/icons.svg#zendesk\"/></svg> Sync Zendesk Agents", "value" => "onclick=\"location.href='index.php?n=agents&import=true'\"");
	$icons[] = array("class" => "btn-primary", "name" => "<svg width=\"1em\" height=\"1em\"><use xlink:href=\"inc/icons.svg#agents\"/></svg> Add Agent", "value" => "data-bs-toggle=\"modal\" data-bs-target=\"#agentAddModal\"");

	echo makeTitle($title, $subtitle, $icons);
	?>

	<?php echo $agentsClass->displayMembers("all"); ?>
</div>



<!-- Modal -->
<div class="modal fade" id="agentAddModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add New Agent</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><svg width="1em" height="1em"><use xlink:href="inc/icons.svg#agents"/></svg> Add Agent</button>
      </div>
    </div>
		</form>
  </div>
</div>
