<?php
$path[type] = "admin";
include "./include/admin_head.ph";
include "../include/ostype.ph";

if(!session_is_registered("$jsboard") || ${$jsboard}[pos] != 1)
print_error($langs[login_err]);

if($mode == "global_chg") {
  $db[rhost] = $db[server];
  $db[rmode] = "";
}

if($db[rmode] == "r") 
  print_error("System Checking NOW !! \n\nSorry, Read only enable.",250,130,1);

$connect = @mysql_connect($db[rhost],$db[user],$db[pass]);
sql_error(mysql_errno(), mysql_error());

mysql_select_db($db[name],$connect);

# password 비교함수 - admin/include/auth.ph
compare_pass($$jsboard);

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check($db[name]);

# 알파벳 구분된 페이지에서 넘어 왔을 경우 페이지를
# 되돌리기 위해 지정
if($ts) $tslink = "?ts=$ts";

###########################################
#          DB에 접속
###########################################

if($mode=='db_del') {
  table_name_check($table_name);

  # table delete
  $table_del = "drop table $table_name";
  $result = mysql_query($table_del,$connect);
  sql_error(mysql_errno(),mysql_error());

  # 게시판 계정에서 사용되는 file 삭제
  exec("$exec[rm] ../data/$table_name");
  mysql_close();
}

if($mode == 'db_create')  {
  $tbl_list = mysql_list_tables($db[name]);

  # 새로만들 계정이름의 존재유무 체크
  table_name_check($new_table);
  # table list 존재 유무 체크
  table_list_check($db[name]);
  # 동일한 이름의 게시판이 있는지 확인
  same_db_check($tbl_list,$new_table);

  #include "include/first_reg.ph";
  $create_table = "CREATE TABLE $new_table ( 
			  no int(6) DEFAULT '0' NOT NULL auto_increment,
			  num int(6) DEFAULT '0' NOT NULL,
			  idx int(6) DEFAULT '0' NOT NULL,
			  date int(11) DEFAULT '0' NOT NULL,
			  host tinytext,
			  name tinytext,
                          rname tinytext,
			  passwd varchar(56),
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

  $passwd_ext = crypt($passwd_ext);

  $insert_data = "INSERT INTO $new_table (no,num,idx,date,host,name,passwd,email,url,title,
                                      text,refer,reyn,reno,rede,reto,html,bofile,
                                      bcfile,bfsize)
                      VALUES ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext','$email_ext',
                              '$url_ext','$subj_msg','$text_msg',0,0,0,0,0,0,'','','')";

  $result = mysql_query($create_table, $connect);
  sql_error(mysql_errno(),mysql_error());
  #$result_insert = mysql_query($insert_data, $connect);

  # 새로운 게시판에 필요한 파일및 디렉토리 생성
  mkdir("../data/$new_table",0700);
  mkdir("../data/$new_table/$upload[dir]",0700);
  chmod("../data/$new_table",0755);
  chmod("../data/$new_table/$upload[dir]",0755);
  copy("../INSTALLER/sample/data/config.ph","../data/$new_table/config.ph");
  chmod("../data/$new_table/config.ph",0644);
  copy("../INSTALLER/sample/data/html_head.ph","../data/$new_table/html_head.ph");
  chmod("../data/$new_table/html_head.ph",0644);
  copy("../INSTALLER/sample/data/html_tail.ph","../data/$new_table/html_tail.ph");
  chmod("../data/$new_table/html_tail.ph",0644);
  copy("../INSTALLER/sample/data/stylesheet.ph","../data/$new_table/stylesheet.ph");
  chmod("../data/$new_table/stylesheet.ph",0644);

  mysql_close();
}

if($mode == "global_chg") {
  mysql_close();
  # quot 변환된 문자를 un quot 한다

  $vars = "<?\n".stripslashes($glob[vars])."\n?>";
  $spam = stripslashes($glob[spam]);

  file_operate("../config/global.ph","w",0,$vars);
  file_operate("../config/spam_list.txt","w",0,$spam);

  $langs[act_complete] = str_replace("\n","\\n",$langs[act_complete]);
  $langs[act_complete] = str_replace("'","\'",$langs[act_complete]);
  echo "<script>\n" .
       "alert('$langs[act_complete]')\n" .
       "window.close()\n</script>";
  exit;

}

Header("Location:admin.php$tslink");

?>
