<?php
$logs = new logs();
$logs->delete_old_logs();
$logsAll = $logs->find_all();
?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Logs</h1>
		<p class="lead">Logs for cron tasks, ticket creation and agent changes.</p>
	</div>

	<p>Example <b>crontab -e</b>:</p>
	<?php
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	?>
	<code># Run Zendesk daily tasks every week day morning:</code><br />
	<code>0 0 * * MON-FRI curl <?php echo $actual_link;?>/cron/daily.php</code><br /><br />
	<code># Run Zendesk weekly tasks every Monday morning:</code><br />
	<code>0 0 * * MON curl <?php echo $actual_link;?>/cron/weekly.php</code><br /><br />
	<code># Run Zendesk monthly tasks every 1st of the month:</code><br />
	<code>0 0 1 * * curl <?php echo $actual_link;?>/cron/monthly.php</code><br /><br />
	<code># Run Zendesk yearly tasks (check every morning):</code><br />
	<code>0 0 * * * curl <?php echo $actual_link;?>/cron/yearly.php</code><br /><br />
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
