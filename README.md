# zendesk_scheduler
A simple web-based utility to create and manage reoccurring zendesk ticket creation

## Getting Started
![Screenshot](/views/screenshot_01.png)

## Installation
* cd to your web directory and run ```git clone https://github.com/dox/zendesk_scheduler```
* Then install (via composer) the [Zendesk API Client](https://github.com/zendesk/zendesk_api_client_php) and [ldaprecord](https://ldaprecord.com)
    * ```composer require zendesk/zendesk_api_client_php```
    * ```composer require directorytree/ldaprecord```
* Create a database in mysql and include the host, database, username and password in config.php
* Modify the inc/config.php file with your LDAP/other settings
* Visit http://yourdomeain/install.php and click 'CLICK HERE TO SETUP TABLES IN YOUR DATABASE'.  This will create the structure for the database
* Check your site it up and running (it should be!)
* Modify youu crontab ```sudo crontab -e``` and include the following (modify for your own folder structure!)

```
0 0 * * MON-FRI curl http://yourdomain/cron/daily.php
0 0 * * MON curl http://yourdomain/cron/weekly.php
0 0 1 * * curl http://yourdomain/cron/monthly.php
0 0 * * * curl http://yourdomain/cron/yearly.php
```

* Go to your web server via HTTP and add your Zendesk agents (you'll need each Agent's ID, as it exists in your Zendesk)
* Add jobs

## Upgrading
Upgrading is as simple as running ```git pull``` in the directory you created above.
