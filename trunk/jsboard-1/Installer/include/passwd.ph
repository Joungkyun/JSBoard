<?php
/****************************************************/
# Specify MySQL Root Password
# mysql을 관리할 root의 password 를 지정
#
$passwd = "";

# apache의 설정파일(httpd.conf)의 경로를 지정
# redhat rpm package의 경우 /etc/httpd/conf/httpd.conf
# debian package의 경우 /etc/apache/httpd.conf
# 수동으로 설치했을 경우 /usr/local/apache/conf/httpd.conf
# 에 위치하는 경우가 많음.
#
$apache_config_file = "/etc/httpd/conf/httpd.conf";

# MySQL 의 db 위치를 적는다. 외부 DB 일경우에는 DB 서버
# 의 ip address 를 지정한다. 로컬일 경우에는 localhost 나
# 127.0.0.1 을 지정한다. 만약 localhost 나 127.0.0.1 이
# 접근이 거부될 경우에는 MySQL 의 socket file 을 직접 지
# 정하도록 한다.
#
# socket file의 위치를 알고 싶다면 
# netstat -an | grep mysql 
# 로 알수가 있다.
#
$mysql_sock = "localhost";
/****************************************************/
?>
