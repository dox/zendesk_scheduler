<?php
$logs = new logs();
$logs->delete_old_logs();
$logsAll = $logs->find_all();
?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4"><svg width="1em" height="1em"><use xlink:href="inc/icons.svg#logs"/></svg> Logs</h1>
		<p class="lead">Logs for cron tasks, ticket creation and agent changes.</p>
	</div>

	<div class="mb-3">
		<p>Example <b>crontab -e</b>:</p>

		<code>
			<ul class="list-unstyled">
				<li># Run Zendesk daily tasks every week day morning:</li>
				<li>0 0 * * MON-FRI cd <?php echo($_SERVER['DOCUMENT_ROOT']); ?>/; php -q cron/daily.php</li>
				<li># Run Zendesk weekly tasks every Monday morning:</li>
				<li>0 0 * * MON cd <?php echo($_SERVER['DOCUMENT_ROOT']); ?>/; php -q cron/weekly.php</li>
				<li># Run Zendesk monthly tasks every 1st of the month:</li>
				<li>0 0 1 * * cd <?php echo($_SERVER['DOCUMENT_ROOT']); ?>/; php -q cron/monthly.php</li>
				<li># Run Zendesk yearly tasks (check every morning):</li>
				<li>0 0 * * * cd <?php echo($_SERVER['DOCUMENT_ROOT']); ?>/; php -q cron/yearly.php</li>
			</ul>
		</code>
	</div>

	<hr />

	<table class="table table-striped">
		<thead>
			<tr>
				<td width="200px">Date</td>
				<td>Description</td>
				<td>Other</td>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($logsAll AS $log) {
				echo $log->display_log();
			}
			?>
		</tbody>
	</table>
</div>
