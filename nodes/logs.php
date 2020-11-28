<!DOCTYPE html>
<html lang="en">
<?php include_once("views/html_head.php");

$logs = new logs();
$logs->delete_old_logs();
$logsAll = $logs->find_all();
?>

<body>
<?php include_once("views/navbar.php"); ?>

<div class="container">
	<div class="px-3 py-3 pt-md-5 pb-md-4 text-center">
		<h1 class="display-4">Logs</h1>
		<p class="lead">Logs for cron tasks, ticket creation and agent changes.</p>
	</div>

	<p>Example <b>crontab -e</b>:</p>
	<code>0 0 * * MON-FRI php -f /var/www/html/zendesk/cron/daily.php</code><br />
	<code>0 0 * * MON php -f /var/www/html/zendesk/cron/weekly.php</code><br />
	<code>0 0 1 * * php -f /var/www/html/zendesk/cron/monthly.php</code><br />

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

	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>
