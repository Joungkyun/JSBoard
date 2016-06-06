<?php
/****************************************************/
# Specify MySQL Root Password
# mysql�� ������ root�� password �� ����
#
$passwd = "";

# apache�� ��������(httpd.conf)�� ��θ� ����
# redhat rpm package�� ��� /etc/httpd/conf/httpd.conf
# debian package�� ��� /etc/apache/httpd.conf
# �������� ��ġ���� ��� /usr/local/apache/conf/httpd.conf
# �� ��ġ�ϴ� ��찡 ����.
#
$apache_config_file = "/etc/httpd/conf/httpd.conf";

# MySQL �� db ��ġ�� ���´�. �ܺ� DB �ϰ�쿡�� DB ����
# �� ip address �� �����Ѵ�. ������ ��쿡�� localhost ��
# 127.0.0.1 �� �����Ѵ�. ���� localhost �� 127.0.0.1 ��
# ������ �źε� ��쿡�� MySQL �� socket file �� ���� ��
# ���ϵ��� �Ѵ�.
#
# socket file�� ��ġ�� �˰� �ʹٸ� 
# netstat -an | grep mysql 
# �� �˼��� �ִ�.
#
$mysql_sock = "localhost";
/****************************************************/
?>
