<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Zendesk Schedule</h1>
		<p class="lead">A simple web-based utility to create and manage reoccurring Zendesk ticket creation.</p>
	</div>

	<div class="pb-3 text-right">
		<a class="btn btn-primary" href="<?php echo zd_url; ?>" role="button">View Zendesk</a>
	</div>

	<div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
    <div class="col">
      <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 fw-normal">Step 1</h4>
      </div>
      <div class="card-body">
        <h1 class="card-title pricing-card-title">Configure</h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li>Follow the install from the README</li>
          <li>Setup the cron tasks on your server</li>
        </ul>
				<a href="https://www.github.com/dox/zendesk_scheduler" role="button" class="btn btn-lg btn-block btn-outline-primary">README</a>
      </div>
    </div>
    </div>
    <div class="col">
      <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 fw-normal">Step 2</h4>
      </div>
      <div class="card-body">
        <h1 class="card-title pricing-card-title">Agents</h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li>Locate each Agent's ID from Zendesk</li>
          <li>Setup the detais here</li>
        </ul>
				<a href="index.php?n=agents" role="button" class="btn btn-lg btn-block btn-outline-primary">Agents</a>
      </div>
    </div>
    </div>
    <div class="col">
      <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 fw-normal">Step 3</h4>
      </div>
      <div class="card-body">
        <h1 class="card-title pricing-card-title">Tickets</h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li>Create scheduled tickets</li>
          <li>Test tickets by running 'now'</li>
        </ul>
				<a href="index.php?n=tickets" role="button" class="btn btn-lg btn-block btn-outline-primary">Tickets</a>
      </div>
    </div>
    </div>
  </div>
</div>
