<?php
/****************************************************/
# $Id: passwd.ph,v 1.10 2009-11-19 14:19:27 oops Exp $
# Specify MySQL Root Password
# mysql을 관리할 root의 password 를 지정
#
$passwd = '';

# apache의 설정파일(httpd.conf)의 경로를 지정
# redhat rpm package의 경우 /etc/httpd/conf/httpd.conf
# debian package의 경우 /etc/apache/httpd.conf
# 수동으로 설치했을 경우 /usr/local/apache/conf/httpd.conf
# 에 위치하는 경우가 많음.
# freebsd 의 port 를 사용할 경우 /usr/local/etc/apache/httpd.conf 임
# 다음 배열 리스트에 존재하지 않는다면, 추가를 해 주도록 한다.
#
$apache_config_file = array (
  '/etc/httpd/conf/httpd.conf',
  '/etc/httpd/conf.d/php.conf',
  '/etc/apache/httpd.conf',
  '/usr/local/apache/conf/httpd.conf',
  '/etc/apache2/apache2.conf',
  '/etc/apache2/mods-enabled/dir.conf',
);

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
$mysql_sock = 'localhost';
/****************************************************/
?>
