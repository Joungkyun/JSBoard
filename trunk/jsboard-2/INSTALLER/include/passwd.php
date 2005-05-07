<?
################################################################################
# Database type
#
# Support value :
#   => mysql3
#   => mysql4
#   => mysql41
#   => pgsql
#
# mysql41 을 선택했을 경우에는 my.cnf 에서 old_password=1 을 적용하는 것을 권장
#
$_db['type'] = 'mysql4';

# MySQL root 의 권한 소지 여부
# MySQL root 의 패스워드를 알면 1, 모르면 0
#
# 만약 DB root 암호를 몰라 0 으로 설정을 하려면 DB root 의 권한을 가지고 있는 사
# 람이 database 를 생성하고 유저 등록과 암호를 등록 해 준 후에 INSTALLER 를 사용
# 할 수 있음. 이 값들은 아래에 등록해 줘야 함.
# 
$_db['root'] = 0;

# Specify DB Root Password
# db 를 관리할 root 의 password 를 지정. 위에서 $_db['root'] 의 값을 0 으로 지정
# 했을 경우에는 DB root 가 알려준 DB 계정 패스워드를 지정함.
#
$_db['pass'] = 'DB_ROOT_PASSWD';

# $_db['root'] = 0 일 경우에는 아래를 설정함
#
# DB root 가 생성해준 database name 을 지정
#
$_db['name'] = '';

# DB root 가 생성해준 database user name 을 지정
#
$_db['user'] = '';

# apache의 설정파일(httpd.conf)의 경로를 지정
# 이 설정은 httpd.conf 의 Group 지시자의 값을 얻어오기 위하여 필요하며, Zento 의
# 경우에는 commonapache.conf 에 Group 지시자가 존재하기 때문에 commonapache.conf
# 의 위치를 지정하도록 함. 스페이스로 구분하여 여러개의 설정파일을 지정해 놓으면
# 인스톨시 설정된 파일들을 모두 검색함.
#
$apache_config_file = '/etc/httpd/conf/httpd.conf '.
                      '/etc/httpd/conf.d/php.conf '.
                      '/etc/apache/httpd.conf '.
                      '/usr/local/apache/conf/httpd.conf';

# DB 의 socket file 위치를 지정. socket file 이 무엇인지 모르겠으면 localhost 나
# 127.0.0.1 을 적도록 한다. DB 서버가 외부에 있을 경우에는 해당 DB서버의 ip 주소
# 나 도메인 이름을 적어 주도록 하며, 이 경우에는 DB서버에서 설정한 접근 권한으로
# 설정을 하여야 한다.
#
# win32 또는 unix socket 를 사용할 수 없는 환경에서는 TCP host 를 지정
#
$_db['sock'] = ":/var/lib/mysql/mysql.sock";

################################################################################
?>
