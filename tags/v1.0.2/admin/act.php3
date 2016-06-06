<?php
include("./include/admin_head.ph");

// password 비교함수 - admin/include/auth.ph
compare_pass($sadmin,$login);

$connect=mysql_connect($db[server],$db[user] ,$db[pass])  or  
              die("$langs[sql_na]" ); 

/* db_name이 존재하지 않으면 아래를 출력합니다. */
exsit_dbname_check($db[name]);

mysql_select_db($db[name],$connect);

if ( $mode != 'manager_config') {

  /******************************************
            DB에 접속을 합니다.
   *****************************************/

  if ($mode=='db_del') {
    if (!$table_name) {
      echo "<script>\nalert('$table_name $langs[n_acc]')\n" .
           "history.back() \n</script>";
      exit;
    }

    /* table delete */
    $table_del = "drop table $table_name";
    $result = mysql_query($table_del,$connect);

    /* 게시판 계정에서 사용되는 file 삭제 */
    exec("$exec[rm] ../data/$table_name");
    mysql_close();
  }

  if ($mode == 'db_create')  {
    $tbl_list = mysql_list_tables($db[name]);

    /* 새로만들 계정이름의 존재유무 체크 */
    new_table_check($new_table);
    /* table list 존재 유무 체크 */
    table_list_check($db[name]);
    /* 동일한 DB가 있는지 확인 */
    same_db_check($tbl_list,$new_table);

    $create_table = "CREATE TABLE $new_table ( 
			  no int(6) DEFAULT '0' NOT NULL auto_increment,
			  num int(6) DEFAULT '0' NOT NULL,
			  idx int(6) DEFAULT '0' NOT NULL,
			  date int(11) DEFAULT '0' NOT NULL,
			  host tinytext,
			  name tinytext,
			  passwd varchar(13),
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

    // 새로운 게시판에 필요한 파일및 디렉토리 생성
    mkdir("../data/$new_table",0700);
    mkdir("../data/$new_table/files",0700);
    chmod("../data/$new_table",0755);
    chmod("../data/$new_table/files",0755);
    copy("./include/sample/config.ph","../data/$new_table/config.ph");
    chmod("../data/$new_table/config.ph",0644);
    copy("./include/sample/html_head.ph","../data/$new_table/html_head.ph");
    chmod("../data/$new_table/html_head.ph",0644);
    copy("./include/sample/html_tail.ph","../data/$new_table/html_tail.ph");
    chmod("../data/$new_table/html_tail.ph",0644);

    // 현재 디렉토리를 변경
    chdir("../data/$new_table");
    exec("$exec[ln] ../../config/themes/white.themes default.themes");    
    chdir("../../admin");

    mysql_close();
  }

  if ($mode == "global_chg") {

    // quot 변환된 문자를 un quot 한다
    $vars = $glob[vars];
    $spam = $glob[spam];
    $vars = stripslashes($vars);
    $spam = stripslashes($spam);

    $fp = fopen("../config/global.ph","w"); 
    fwrite($fp,$vars); 
    fclose($fp);

    $gp = fopen("../config/spam_list.txt", "w"); 
    fwrite($gp,$spam); 
    fclose($gp);

    // 현재 디렉토리를 변경
    chdir("../config");
    exec("$exec[ln] themes/$glob[theme].themes default.themes");

    echo "<script>\n" .
         "alert('$langs[act_complete]')\n" .
         "window.close()\n</script>";
    exit;

  }

  Header("Location:admin.php3");

} else {
  if ($admincenter_pass && $readmincenter_pass) {
    if ($admincenter_pass == $readmincenter_pass) {

      // 입력 받은 패스워드를 crypt 암호화
      $ad_pass = crypt($admincenter_pass);

      $configfile = "./include/config.ph";
      $fp = fopen($configfile,"r");
      $admininfo = fread($fp,filesize($configfile));
      fclose($fp);

      $admininfo = eregi_replace("sadmin\[passwd\] = (\"[a-z0-9\.\/]*\")","sadmin[passwd] = \"$ad_pass\"",$admininfo);

      $fp = fopen($configfile,"w"); 
      fwrite($fp, $admininfo); 
      fclose($fp);

      complete_adminpass();

    } else {
      admin_pass_error();
    }
  } else {
    echo "<script>\n" .
         "alert('$langs[a_act_cp]')\n" .
         "history.back() \n" .
         "</script>";
    exit;
  }
}

?>
