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

# password ���Լ� - admin/include/auth.ph
compare_pass($$jsboard);

# db_name�� �������� ������ �Ʒ��� ����մϴ�.
exsit_dbname_check($db[name]);

# ���ĺ� ���е� ���������� �Ѿ� ���� ��� ��������
# �ǵ����� ���� ����
if($ts) $tslink = "?ts=$ts";

###########################################
#          DB�� ����
###########################################

if($mode=='db_del') {
  table_name_check($table_name);

  # table delete
  $table_del = "drop table $table_name";
  $result = mysql_query($table_del,$connect);
  sql_error(mysql_errno(),mysql_error());

  # �Խ��� �������� ���Ǵ� file ����
  exec("$exec[rm] ../data/$table_name");
  mysql_close();
}

if($mode == 'db_create')  {
  $tbl_list = mysql_list_tables($db[name]);

  # ���θ��� �����̸��� �������� üũ
  table_name_check($new_table);
  # table list ���� ���� üũ
  table_list_check($db[name]);
  # ������ �̸��� �Խ����� �ִ��� Ȯ��
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

  # ���ο� �Խ��ǿ� �ʿ��� ���Ϲ� ���丮 ����
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
  # quot ��ȯ�� ���ڸ� un quot �Ѵ�

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
