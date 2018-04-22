#!/bin/bash

export DB_NAME="lggr"

systemctl start mysql.service
systemctl start apache2.service
sleep 60

mysqladmin create $DB_NAME
mysql -e "GRANT ALL ON lggr.* TO lggr@'%' IDENTIFIED BY 'lggr'; FLUSH PRIVILEGES" $DB_NAME
mysql $DB_NAME <doc/db.sql