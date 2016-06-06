<?php
# $Id: act.php,v 1.10 2009-11-19 17:08:55 oops Exp $
session_start(); // session�� �����Ѵ�.
include_once '../include/print.ph';
parse_query_str();

$path['type'] = 'Install';
if ($langss == 'ko') $langs['code'] = 'ko';
else $langs['code'] = 'en';

include_once '../include/ostype.ph';
include_once '../include/lang.ph';
include_once '../include/get.ph';
include_once '../include/error.ph';
include_once './include/passwd.ph';
include_once './include/check.ph';

# Password Checkk
inst_pwcheck($passwd,$mysqlpass,$langs['act_pw']);

# MySQL login
$connect = mysql_connect($mysql_sock,'root',$passwd) or die($langs['inst_sql_err']);
$indb['lists'] = mysql_list_dbs($connect);
$indb['num'] = mysql_num_rows($indb['lists']);
mysql_select_db('mysql', $connect);

# install.php ���� �Ѿ�� �������� üũ
$indb['check'] = inst_check();

# DB ���� ���� chek�� ����� MySQL�� ����
if ($indb['check']) {
  # MySQL�� DB ����
  $create['dbname'] = "create database {$dbinst['name']}";
  $result['dbname'] = mysql_query($create['dbname'],$connect);

  # �ܺ� DB �� ��쿡�� ���� ������ �ܺη� �����Ѵ�.
  if($mysql_sock != '127.0.0.1' &&
     $mysql_sock != 'localhost' &&
     !preg_match('/mysql\.sock/i',$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = 'localhost';

  # user �� db ���(grant �� �̿�)
  $create['regis'] = "GRANT all privileges ON {$dbinst['name']}.*
                       TO {$dbinst['user']}@{$access_permit}
                       IDENTIFIED BY '{$dbinst['pass']}'";
  $result['regis'] = mysql_db_query('mysql',$create['regis'],$connect);

  # test �Խ����� ����
  mysql_select_db($dbinst['name'],$connect);

  $create['table'] = "CREATE TABLE test ( 
    no int(6) NOT NULL auto_increment,
    num int(6) DEFAULT '0' NOT NULL,
    idx int(6) DEFAULT '0' NOT NULL,
    date int(11) DEFAULT '0' NOT NULL,
    host tinytext,
    name tinytext,
    passwd varchar({$ostypes['pfield']}),
    email tinytext,
    url tinytext,
    title tinytext,
    text mediumtext,
    refer int(6) DEFAULT '0' NOT NULL,
    reyn int(1) DEFAULT '0' NOT NULL,
    reno int(6) DEFAULT '0' NOT NULL,
    rede int(6) DEFAULT '0' NOT NULL,
    reto int(6) DEFAULT '0' NOT NULL,
    html int(1) DEFAULT '1' NOT NULL,
    moder int(1) DEFAULT '0' NOT NULL,
    bofile varchar(100),
    bcfile varchar(100),
    bfsize int(4),
    KEY no (no),
    KEY num (num),
    KEY idx (idx),
    KEY reno (reno),
    KEY date (date),
    KEY reto (reto),
    PRIMARY KEY (no))";

  require '../admin/include/first_reg.ph';
  $passwd_ext = crypt($passwd_ext);

  $create['data'] = "INSERT INTO test
    VALUES ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
            '$email_ext','$url_ext','$subj_msg','$text_msg',
            0,0,0,0,0,0,0,'','','')";

  $result['table'] = mysql_query($create['table'], $connect);
  $result['data'] = mysql_query($create['data'], $connect);

  # ���� ��ϵ� user������ ���� MySQL reload
  $create['reload'] = 'flush privileges';
  $result['reload'] = mysql_query($create['reload'], $connect );

  # ���� ���Ͽ� DB ������ �Է�
  $create['gfile'] = '../config/global.ph';
  $create['str'] = file_operate($create['gfile'],'r',"Can't open {$create['gfile']}");

  $create['str'] = str_replace('DBserver',$mysql_sock,$create['str']);
  $create['str'] = str_replace('DBname',$dbinst['name'],$create['str']);
  $create['str'] = str_replace('DBpass',$dbinst['pass'],$create['str']);
  $create['str'] = str_replace('DBuser',$dbinst['user'],$create['str']);

  # spam ���� ����
  mt_srand((double) microtime() * 1000000);
  $create['spam1'] = mt_rand(10001,99999);
  $create['spam2'] = mt_rand(11,99);
  $create['spam3'] = mt_rand(11,99);
  $create['str'] = str_replace('@SPAM1@',$create['spam1'],$create['str']);
  $create['str'] = str_replace('@SPAM2@',$create['spam2'],$create['str']);
  $create['str'] = str_replace('@SPAM3@',$create['spam3'],$create['str']);

  file_operate($create['gfile'],'w',"Can't update {$create['gfile']}",$create['str']);

  # ����� �������� �ʱ�ȭ
  $dbinst['name'] = '';
  $dbinst['user'] = '';
  $dbinst['pass'] = '';

} else print_error($langs['inst_error']);

mysql_close();

echo <<<EOF
<script>
  document.location='session.php?mode=first&langss={$langss}'
</script>
EOF;

?>
