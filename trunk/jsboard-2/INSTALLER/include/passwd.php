<?php
#########################################################
# MySQL root �� ���� ���� ����
# $Id: passwd.php,v 1.8 2014-02-28 21:37:17 oops Exp $
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
