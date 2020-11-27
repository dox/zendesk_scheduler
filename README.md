# zendesk_scheduler
A simple web-based utility to create and manage reoccurring zendesk ticket creation

## Getting Started
![Screenshot](/views/screenshot_01.png)

## Installation
* cd to your web directory and run ```git clone https://github.com/dox/zendesk_scheduler```
* Then install (via composer) the [Zendesk API Client](https://github.com/zendesk/zendesk_api_client_php) and [ldaprecord](https://ldaprecord.com)
** ```composer require zendesk/zendesk_api_client_php```
** ```composer require directorytree/ldaprecord```
* Create a database in mysql and in it run the 'mysql_import.sql' file.  This will create the structure for the database
* Modify the inc/config.php file with your settings
* Modify yoru crontab ```sudo crontab -e``` and include the following (modify for your own folder structure!)

```
0 0 * * MON-FRI php -f /var/www/html/zendesk/cron/daily.php
0 0 * * MON php -f /var/www/html/zendesk/cron/weekly.php
0 0 1 * * php -f /var/www/html/zendesk/cron/monthly.php
```

* Go to your web server via HTTP and add your Zendesk agents (you'll need each Agent's ID, as it exists in your Zendesk)
* Add jobs
