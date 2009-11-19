<?php
/****************************************************/
# $Id: passwd.ph,v 1.10 2009-11-19 14:19:27 oops Exp $
# Specify MySQL Root Password
# mysql�� ������ root�� password �� ����
#
$passwd = '';

# apache�� ��������(httpd.conf)�� ��θ� ����
# redhat rpm package�� ��� /etc/httpd/conf/httpd.conf
# debian package�� ��� /etc/apache/httpd.conf
# �������� ��ġ���� ��� /usr/local/apache/conf/httpd.conf
# �� ��ġ�ϴ� ��찡 ����.
# freebsd �� port �� ����� ��� /usr/local/etc/apache/httpd.conf ��
# ���� �迭 ����Ʈ�� �������� �ʴ´ٸ�, �߰��� �� �ֵ��� �Ѵ�.
#
$apache_config_file = array (
  '/etc/httpd/conf/httpd.conf',
  '/etc/httpd/conf.d/php.conf',
  '/etc/apache/httpd.conf',
  '/usr/local/apache/conf/httpd.conf',
  '/etc/apache2/apache2.conf',
  '/etc/apache2/mods-enabled/dir.conf',
);

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
$mysql_sock = 'localhost';
/****************************************************/
?>
