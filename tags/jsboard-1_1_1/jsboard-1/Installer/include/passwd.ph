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

/****************************************************/
?>
