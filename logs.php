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
	
	<p>A copy of the current <b>crontab -e</b>:</p>
	<samp>
		0 0 * * MON-FRI php -f /var/www/html/zendesk/cron/daily.php
		0 0 * * MON php -f /var/www/html/zendesk/cron/weekly.php
		0 0 1 * * php -f /var/www/html/zendesk/cron/monthly.php
	</samp>
	
	<h4>Logs</h4>
	<?php
	foreach ($logsAll AS $log) {
		echo $log->display_log();
	}
	?>
	
	<?php include_once("views/footer.php"); ?>
</div> <!-- /container -->
</body>
</html>

