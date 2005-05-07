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
# mysql41 �� �������� ��쿡�� my.cnf ���� old_password=1 �� �����ϴ� ���� ����
#
$_db['type'] = 'mysql4';

# MySQL root �� ���� ���� ����
# MySQL root �� �н����带 �˸� 1, �𸣸� 0
#
# ���� DB root ��ȣ�� ���� 0 ���� ������ �Ϸ��� DB root �� ������ ������ �ִ� ��
# ���� database �� �����ϰ� ���� ��ϰ� ��ȣ�� ��� �� �� �Ŀ� INSTALLER �� ���
# �� �� ����. �� ������ �Ʒ��� ����� ��� ��.
# 
$_db['root'] = 0;

# Specify DB Root Password
# db �� ������ root �� password �� ����. ������ $_db['root'] �� ���� 0 ���� ����
# ���� ��쿡�� DB root �� �˷��� DB ���� �н����带 ������.
#
$_db['pass'] = 'DB_ROOT_PASSWD';

# $_db['root'] = 0 �� ��쿡�� �Ʒ��� ������
#
# DB root �� �������� database name �� ����
#
$_db['name'] = '';

# DB root �� �������� database user name �� ����
#
$_db['user'] = '';

# apache�� ��������(httpd.conf)�� ��θ� ����
# �� ������ httpd.conf �� Group �������� ���� ������ ���Ͽ� �ʿ��ϸ�, Zento ��
# ��쿡�� commonapache.conf �� Group �����ڰ� �����ϱ� ������ commonapache.conf
# �� ��ġ�� �����ϵ��� ��. �����̽��� �����Ͽ� �������� ���������� ������ ������
# �ν���� ������ ���ϵ��� ��� �˻���.
#
$apache_config_file = '/etc/httpd/conf/httpd.conf '.
                      '/etc/httpd/conf.d/php.conf '.
                      '/etc/apache/httpd.conf '.
                      '/usr/local/apache/conf/httpd.conf';

# DB �� socket file ��ġ�� ����. socket file �� �������� �𸣰����� localhost ��
# 127.0.0.1 �� ������ �Ѵ�. DB ������ �ܺο� ���� ��쿡�� �ش� DB������ ip �ּ�
# �� ������ �̸��� ���� �ֵ��� �ϸ�, �� ��쿡�� DB�������� ������ ���� ��������
# ������ �Ͽ��� �Ѵ�.
#
# win32 �Ǵ� unix socket �� ����� �� ���� ȯ�濡���� TCP host �� ����
#
$_db['sock'] = ":/var/lib/mysql/mysql.sock";

################################################################################
?>
