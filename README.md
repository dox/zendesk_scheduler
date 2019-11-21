# zendesk_scheduler
A simple web-based utility to create and manage reoccurring zendesk ticket creation

## Getting Started
![Screenshot](/views/screenshot-01.png)

## Installation
* Copy the directory to your webserver
* Create a database in mysql and in it run the 'mysql_import.sql' file.  This will create the structure for the database
* Go to your web server via HTTP and add your Zendesk agents (you'll need each Agent's ID, as it exists in your Zendesk)
* Add jobs
* You'll need to schedule cron jobs on your server to trigger the scheduling of tickets.  I'd suggest something like the following (change for the location to your files!!)
```
0 0 * * MON-FRI php -f /var/www/html/zendesk/cron/daily.php
0 0 * * MON php -f /var/www/html/zendesk/cron/weekly.php
0 0 1 * * php -f /var/www/html/zendesk/cron/monthly.php
```
* You'll need to install the excellent [Zendesk API Client](https://github.com/zendesk/zendesk_api_client_php) into the same directory as the above files using composer
