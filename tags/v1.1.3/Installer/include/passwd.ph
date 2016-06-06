<?php
/****************************************************/
# Specify MySQL Root Password
# mysql�� ������ root�� password �� ����

$passwd = "";

# apache�� ��������(httpd.conf)�� ��θ� ����
# redhat rpm package�� ��� /etc/httpd/conf/httpd.conf
# debian package�� ��� /etc/apache/httpd.conf
# �������� ��ġ���� ��� /usr/local/apache/conf/httpd.conf
# �� ��ġ�ϴ� ��찡 ����.

$apache_config_file = "/etc/httpd/conf/httpd.conf";

# MySQL�� socket file ��ġ�� ����. socket file�� ����
# ���� �𸣰����� localhost �� 127.0.0.1 �� ������ ��
# ��. DB������ �ܺο� ���� ��쿡�� �ش� DB������ ip
# address�� domain name�� �����ֵ��� �ϸ�, �� ��쿡��
# DB������ mysql���� ������ ���� �������� ������ �Ͽ���
# �Ѵ�.

$mysql_sock = ":/var/lib/mysql/mysql.sock";

/****************************************************/
?>
