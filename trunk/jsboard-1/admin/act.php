<?php
include "./include/admin_head.ph";
include "../include/ostype.ph";

# password 비교함수 - admin/include/auth.ph
compare_pass($sadmin,$login);

$connect=mysql_connect($db[server],$db[user] ,$db[pass])  or  
              die("$langs[sql_na]" ); 

# db_name이 존재하지 않으면 아래를 출력합니다.
exsit_dbname_check($db[name]);

mysql_select_db($db[name],$connect);

if( $mode != "manager_config") {

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

    $create_table = "CREATE TABLE $new_table ( 
			  no int(6) DEFAULT '0' NOT NULL auto_increment,
			  num int(6) DEFAULT '0' NOT NULL,
			  idx int(6) DEFAULT '0' NOT NULL,
			  date int(11) DEFAULT '0' NOT NULL,
			  host tinytext,
			  name tinytext,
			  passwd varchar($ostypes[pfield]),
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

    $passwd_ext = crypt($passwd_ext);

    $insert_data = "insert into $new_table values ('',1,1,$date,'$host_ext','$name_ext','$passwd_ext',
                    '$email_ext','$url_ext','$subj_msg','$text_msg',0,0,0,0,0,0,0,'','','')";

    $result = mysql_query($create_table, $connect);
    $result_insert = mysql_query($insert_data, $connect);

    # 새로운 게시판에 필요한 파일및 디렉토리 생성
    mkdir("../data/$new_table",0700);
    mkdir("../data/$new_table/$upload[dir]",0700);
    chmod("../data/$new_table",0755);
    chmod("../data/$new_table/$upload[dir]",0755);
    copy("../Installer/sample/$ostypes[name]/config.ph","../data/$new_table/config.ph");
    chmod("../data/$new_table/config.ph",0644);
    copy("../Installer/sample/$ostypes[name]/html_head.ph","../data/$new_table/html_head.ph");
    chmod("../data/$new_table/html_head.ph",0644);
    copy("../Installer/sample/$ostypes[name]/html_tail.ph","../data/$new_table/html_tail.ph");
    chmod("../data/$new_table/html_tail.ph",0644);

    # 현재 디렉토리를 변경
    chdir("../data/$new_table");
    if(file_exists("default.themes")) unlink("default.themes");
    symlink("../../config/themes/basic.themes","default.themes");
    chdir("../../admin");

    mysql_close();
  }

  if($mode == "global_chg") {

    # quot 변환된 문자를 un quot 한다
    $vars = stripslashes($glob[vars]);
    $spam = stripslashes($glob[spam]);
    $br   = stripslashes($glob[brlist]);

    file_operate("../config/global.ph","w",0,$vars);
    file_operate("../config/spam_list.txt","w",0,$spam);
    file_operate("../config/allow_browser.txt","w",0,$br);

    # 현재 디렉토리를 변경
    chdir("../config");
    if(file_exists("default.themes")) unlink("default.themes");
    symlink("themes/$glob[theme].themes","default.themes");

    $langs[act_complete] = str_replace("\n","\\n",$langs[act_complete]);
    $langs[act_complete] = str_replace("'","\'",$langs[act_complete]);
    echo "<script>\n" .
         "alert('$langs[act_complete]')\n" .
         "window.close()\n</script>";
    exit;

  }

  Header("Location:admin.php$tslink");

} else {
  if($admincenter_pass && $readmincenter_pass) {
    if($admincenter_pass == $readmincenter_pass) {

      # 입력 받은 패스워드를 crypt 암호화
      $ad_pass = crypt($admincenter_pass);
      $ad_pass = str_replace("\$","\\\$",$ad_pass);

      $configfile = "./include/config.ph";
      $admininfo = file_operate($configfile,"r","Don't open $configfile");
      $admininfo = eregi_replace("sadmin\[passwd\] = (\"[a-z0-9\.\/\$]*\")","sadmin[passwd] = \"$ad_pass\"",$admininfo);

      file_operate($configfile,"w","Can't update $configfile",$admininfo);

      complete_adminpass();
    } else admin_pass_error();
  } else print_error($langs[a_act_cp]);
}

?>
