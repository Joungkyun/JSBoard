<?php
# $Id: act.php,v 1.10 2009-11-19 17:08:55 oops Exp $
session_start(); // session을 시작한다.
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

# install.php 에서 넘어온 변수값들 체크
$indb['check'] = inst_check();

# DB 정보 유무 chek에 통과시 MySQL에 셋팅
if ($indb['check']) {
  # MySQL에 DB 생성
  $create['dbname'] = "create database {$dbinst['name']}";
  $result['dbname'] = mysql_query($create['dbname'],$connect);

  # 외부 DB 일 경우에는 접근 권한을 외부로 지정한다.
  if($mysql_sock != '127.0.0.1' &&
     $mysql_sock != 'localhost' &&
     !preg_match('/mysql\.sock/i',$mysql_sock))
    $access_permit = $mysql_sock;
  else $access_permit = 'localhost';

  # user 및 db 등록(grant 문 이용)
  $create['regis'] = "GRANT all privileges ON {$dbinst['name']}.*
                       TO {$dbinst['user']}@{$access_permit}
                       IDENTIFIED BY '{$dbinst['pass']}'";
  $result['regis'] = mysql_db_query('mysql',$create['regis'],$connect);

  # test 게시판을 생성
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

  # 새로 등록된 user정보를 위해 MySQL reload
  $create['reload'] = 'flush privileges';
  $result['reload'] = mysql_query($create['reload'], $connect );

  # 설정 파일에 DB 정보를 입력
  $create['gfile'] = '../config/global.ph';
  $create['str'] = file_operate($create['gfile'],'r',"Can't open {$create['gfile']}");

  $create['str'] = str_replace('DBserver',$mysql_sock,$create['str']);
  $create['str'] = str_replace('DBname',$dbinst['name'],$create['str']);
  $create['str'] = str_replace('DBpass',$dbinst['pass'],$create['str']);
  $create['str'] = str_replace('DBuser',$dbinst['user'],$create['str']);

  # spam 변수 설정
  mt_srand((double) microtime() * 1000000);
  $create['spam1'] = mt_rand(10001,99999);
  $create['spam2'] = mt_rand(11,99);
  $create['spam3'] = mt_rand(11,99);
  $create['str'] = str_replace('@SPAM1@',$create['spam1'],$create['str']);
  $create['str'] = str_replace('@SPAM2@',$create['spam2'],$create['str']);
  $create['str'] = str_replace('@SPAM3@',$create['spam3'],$create['str']);

  file_operate($create['gfile'],'w',"Can't update {$create['gfile']}",$create['str']);

  # 등록후 변수값들 초기화
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
