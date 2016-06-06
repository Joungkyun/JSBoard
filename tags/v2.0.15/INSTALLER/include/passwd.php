<?
#########################################################
# MySQL root �� ���� ���� ����
# $Id: passwd.php,v 1.6 2009-11-19 05:29:49 oops Exp $
# MySQL root �� �н����带 �˸� 1, �𸣸� 0
#
# ���� MySQL �н����带 ���� 0 ���� ������ �Ϸ��� MySQL
# root �� ������ ������ �ִ� ����� database �� �����ϰ�
# ���� ��ϰ� �н����带 ����� �� �Ŀ� INSTALLER �� ���
# �� �� ����. �� ������ �Ʒ��� ����� ��� ��.
# 
$mysqlroot = 1;

# Specify MySQL Root Password
# mysql�� ������ root�� password �� ����
# ������ $mysqlroot �� ���� 0 ���� �������� ��쿡�� MySQL
# root �� �˷��� mysql ���� �н����带 ������.
#
$passwd = "MySQL_ROOT_PASSWORD";

# $mysqlroot = 0 �� ��쿡�� �Ʒ��� ������
#
# mysql root �� �������� database name �� ����
$mysqldatabasename = "";

# mysql root �� �������� database user name �� ����
$mysqlusername = "";

# apache�� ��������(httpd.conf)�� ��θ� ����
# redhat rpm package�� ��� /etc/httpd/conf/httpd.conf
# debian package�� ��� /etc/apache/httpd.conf
# �������� ��ġ���� ��� /usr/local/apache/conf/httpd.conf
# �� ��ġ�ϴ� ��찡 ����.
# �� ������ httpd.conf �� Group �������� ���� ������ ��
# �Ͽ� �ʿ��ϹǷ�, Zento Linux �� ��쿡�� commonapache.conf
# �� Group �����ڰ� �����ϱ� ������, commonapache.conf ��
# ��ġ�� �����ϵ��� ��
# �����̽��� �����Ͽ� �������� ���������� ������ ������
# �ν���� ������ ���ϵ��� ��� �˻���.
#
$apache_config_file = array (
  '/etc/httpd/conf/httpd.conf',
  '/etc/httpd/conf.d/php.conf',
  '/etc/apache/httpd.conf',
  '/usr/local/apache/conf/httpd.conf',
  '/etc/apache2/apache2.conf',
  '/etc/apache2/mods-enabled/dir.conf',
);

# MySQL�� socket file ��ġ�� ����. socket file�� ��������
# �𸣰����� localhost �� 127.0.0.1 �� ������ �Ѵ�. DB ��
# ���� �ܺο� ���� ��쿡�� �ش� DB������ ip address�� ��
# ���� �̸��� �����ֵ��� �ϸ�, �� ��쿡�� DB������ mysql
# ���� ������ ���� �������� ������ �Ͽ��� �Ѵ�. ��
#  mysql -u USER_NAME -p DB_NAME -h DB_address
# �� ������ ������ �����ؾ� �Ѵ�.
#
# socket file�� ��ġ�� �˰� �ʹٸ� mysql_config --socket
# ������� �˼��� �ִ�.
#

$mysql_sock = ":/var/lib/mysql/mysql.sock";
#########################################################
?>
