#!/bin/bash
command=$1
if [ "$command" == "start" ] || [ "$command" == "" ];then
	echo "stating nginx"
	sudo systemctl restart nginx.service
	echo "stating php-fpm"
	sudo systemctl restart php-fpm.service
	echo "stating mariadb"
	sudo systemctl restart mariadb
	echo "processlist"
	sudo ps -aux | grep "nginx\|php-fpm\|mariadb"
	#sudo tail -f /var/log/nginx/composer-test.dwizzel.local-error.log
elif [ "$command" == "stop" ];then
	sudo systemctl stop nginx.service
	sudo systemctl stop php-fpm.service
	sudo systemctl stop mariadb
else
	echo "start|stop"
fi	
exit 0
