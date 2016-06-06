<?
#########################################################
# MySQL root 의 권한 소지 여부
# $Id: passwd.php,v 1.6 2009-11-19 05:29:49 oops Exp $
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

# apache의 설정파일(httpd.conf)의 경로를 지정
# redhat rpm package의 경우 /etc/httpd/conf/httpd.conf
# debian package의 경우 /etc/apache/httpd.conf
# 수동으로 설치했을 경우 /usr/local/apache/conf/httpd.conf
# 에 위치하는 경우가 많음.
# 이 설정은 httpd.conf 의 Group 지시자의 값을 얻어오기 위
# 하여 필요하므로, Zento Linux 의 경우에는 commonapache.conf
# 에 Group 지시자가 존재하기 때문에, commonapache.conf 의
# 위치를 지정하도록 함
# 스페이스로 구분하여 여러개의 설정파일을 지정해 놓으면
# 인스톨시 설정된 파일들을 모두 검색함.
#
$apache_config_file = array (
  '/etc/httpd/conf/httpd.conf',
  '/etc/httpd/conf.d/php.conf',
  '/etc/apache/httpd.conf',
  '/usr/local/apache/conf/httpd.conf',
  '/etc/apache2/apache2.conf',
  '/etc/apache2/mods-enabled/dir.conf',
);

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
