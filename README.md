Owncloud-nagios
===============
Nagios monitoring of owncloud on linux

This plugin was written to monitor statistics from Owncloud in nagios.
first-release:
	- Quota limit / user and space utilisation

*UPDATE 19-5-2020*

Added a file that makes it possible to use mysql only, so it no longer needs du (works better on slow storage)

howto:

- Copy the du.php or the du-mysql.php somewhere on your owncloud server
- edit crontab as user www-data or low-access user, and add " * /15  *  *  *  *  php -f /path/to/du.php >/tmp/quota" OR " * /15  *  *  *  *  php -f /path/to/du-mysql.php >/tmp/quota" 
- on nagios, edit crontab as user nagios, (USE SSH KEYS for access and add " * /15 * * * * scp root@owncloud:/tmp/quota /tmp/quota-owncloud"
- copy the file check_owncloud_quota to your <nagios>/libexec dir (where all your other scripts for nagios probably reside).
- edit <nagios>/etc/objects/commands.cfg and add the commands specified in the provided commands.cfg
- edit <nagios>/etc/objects/owncloud-server-file.cfg and add a definition for each user, as illustrated in the provided user.cfg
  -	this may also be scripted with a bash-for loop (as i have done considering the many users i have on owncloud)


(Updated for owncloud server v 7.0)



(TESTERS NEEDED)
-Debian 64 bit wheezy tested
