<?php
/****************************************************/
# Specify MySQL Root Password
# mysql을 관리할 root의 password 를 지정

$passwd = "";

# apache의 설정파일(httpd.conf)의 경로를 지정
# redhat rpm package의 경우 /etc/httpd/conf/httpd.conf
# debian package의 경우 /etc/apache/httpd.conf
# 수동으로 설치했을 경우 /usr/local/apache/conf/httpd.conf
# 에 위치하는 경우가 많음.

$apache_config_file = "/etc/httpd/conf/httpd.conf";

# MySQL의 socket file 위치를 지정. socket file이 무엇
# 인지 모르겠으면 localhost 나 127.0.0.1 을 적도록 한
# 다. DB서버가 외부에 있을 경우에는 해당 DB서버의 ip
# address나 domain name을 적어주도록 하며, 이 경우에는
# DB서버의 mysql에서 설정한 접근 권한으로 설정을 하여야
# 한다.

$mysql_sock = ":/var/lib/mysql/mysql.sock";

/****************************************************/
?>
