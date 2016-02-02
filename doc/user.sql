# create the following three mysql users:

# used by syslog-ng for inserting new data, referenced in /etc/syslog-ng/conf.d/08newlogsql.conf
GRANT INSERT,SELECT,UPDATE ON logger.* TO logger@localhost IDENTIFIED BY 'xxx';

# used by the web gui for normal viewing, referenced in inc/config_class.php
GRANT SELECT ON logger.* TO logviewer@localhost IDENTIFIED BY 'xxx';

# used by clean up cron job and for archiving, referenced in inc/adminconfig_class.php
GRANT SELECT,UPDATE,DELETE ON logger.* TO loggeradmin@localhost IDENTIFIED BY 'xxx';

# activate changes
FLUSH PRIVILEGES;
