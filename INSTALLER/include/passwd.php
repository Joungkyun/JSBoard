<?php
#########################################################
# MySQL root 의 권한 소지 여부
# $Id: passwd.php,v 1.9 2014-03-02 17:11:29 oops Exp $
# MySQL root 의 패스워드를 알면 1, 모르면 0
#
# 만약 MySQL 패스워드를 몰라 0 으로 설정을 하려면 MySQL
# root 의 권한을 가지고 있는 사람이 database 를 생성하고
# 유저 등록과 패스워드를 등록해 준 후에 INSTALLER 를 사용
# 할 수 있음. 이 값들은 아래에 등록해 줘야 함.
# 
$mysqlroot = 1;

# Specify MySQL Root Password
# mysql을 관리할 root의 password 를 지정
# 위에서 $mysqlroot 의 값을 0 으로 지정했을 경우에는 MySQL
# root 가 알려준 mysql 계정 패스워드를 지정함.
#
$passwd = "MySQL_ROOT_PASSWORD";

# $mysqlroot = 0 일 경우에는 아래를 설정함
#
# mysql root 가 생성해준 database name 을 지정
$mysqldatabasename = "";

# mysql root 가 생성해준 database user name 을 지정
$mysqlusername = "";

# MySQL의 socket file 위치를 지정. socket file이 무엇인지
# 모르겠으면 localhost 나 127.0.0.1 을 적도록 한다. DB 서
# 버가 외부에 있을 경우에는 해당 DB서버의 ip address나 도
# 메인 이름을 적어주도록 하며, 이 경우에는 DB서버의 mysql
# 에서 설정한 접근 권한으로 설정을 하여야 한다. 즉
#  mysql -u USER_NAME -p DB_NAME -h DB_address
# 로 쉘에서 접근이 가능해야 한다.
#
# socket file의 위치를 알고 싶다면 mysql_config --socket
# 명령으로 알수가 있다.
#

$mysql_sock = ":/var/lib/mysql/mysql.sock";
#########################################################
?>
