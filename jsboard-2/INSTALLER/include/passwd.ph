<?
#########################################################
# Specify MySQL Root Password
# mysql을 관리할 root의 password 를 지정
#
$passwd = "MySQL_ROOT_PASSWORD";

# apache의 설정파일(httpd.conf)의 경로를 지정
# redhat rpm package의 경우 /etc/httpd/conf/httpd.conf
# debian package의 경우 /etc/apache/httpd.conf
# 수동으로 설치했을 경우 /usr/local/apache/conf/httpd.conf
# 에 위치하는 경우가 많음.
#
$apache_config_file = "/etc/httpd/conf/httpd.conf";

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
