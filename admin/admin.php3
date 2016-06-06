<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org  http://www.oops.org                     *
*                                                                       *
************************************************************************/


include("./include/agent.ph");
include("./include/html_head.ph") ;
include("../include/db.ph") ;
include("./include/query.ph") ;
include("./include/boardinfo.ph");

$super_user = board_info($super_user);
$lang = superpass_info($lang) ;

include("../include/multi_lang.ph");

$login_pass = crypt("$login_pass","oo") ;


if ( !$login_pass ) {
  echo ("<script>\n" .
        "alert('$nopasswd')\n" .
        "history.back() \n" .
        "</script>" );
  exit ;
}

if ( $login_pass != $super_user ) {
  echo ("<script>\n" .
        "alert('$pass_alert')\n" .
        "document.location='./cookie.php3?mode=logout'\n" .
        "</script>" );
  exit ;
}

if ($lang == "ko") { netscape_browser() ; }

/* MySQL 서버에 연결한다 */

$connect=mysql_connect( $db_server, "$db_user" , "$db_pass" )  or  
                   die( "Unable to connect to SQL server" ); 

// db_name이 존재하지 않으면 아래를 출력합니다.
if(!$db_name)  {

  echo("<table width=100% height=100%>\n" .
       "<tr>\n" .
       "<td colspan=5 align=center><b><br><br>$nodb<br><br><br></b></td>\n" .
       "</tr></table> ");

}


if($db_name && !$table) {

  $tbl_list = mysql_list_tables($db_name);


  if(!mysql_list_tables($db_name)) {

    echo("<table width=100% height=100%>\n" .
         "<tr>\n" .
         "<td colspan=5 align=center><b><br><br>$nodb<br><br><br></b></td>\n" .
         "</tr></table> ");
	
  }

  $tbl_num=mysql_num_rows($tbl_list);

  echo ("<table border=0 cellpadding=1 cellspacing=1 width=90%>\n" .
        "<tr align=center bgcolor=#555555>\n" .
        "<td rowspan=2><font color=white id=subj>Board Name</font></td>\n" .
        "<td colspan=2><font color=white id=subj>Articles</font></td>\n" .
        "<td rowspan=2><font color=white id=subj>Options</font></td>\n" .
        "<td rowspan=2><font color=white id=subj>Remove</font></td>\n" .
        "</tr>\n" .
        "\n" .
        "<tr align=center bgcolor=#555555>\n" .
        "<td><font color=white>Today</font></td>\n" .
        "<td><font color=white>Total</font></td>\n" .
        "</tr>" );


  if (!$start && !$page) { $start = 0 ; $page = 1; }
  else if ($page > 1) { $start = $page*$scale-$scale ; }

  $nowpage = $page ;
  $until = $start+$scale ;
  $priv = $page-1 ;   
  $next = $page+1 ;

  if ($tbl_num > "1") {

    for($i=$start ; $i<$until ; $i++) {

      if ($i < $tbl_num) {

        // table list 를 불러 옵니다.
        $table_name[$i] = mysql_tablename($tbl_list,$i) ;

        if ( $table_name[$i] != "BoardInformation" ) {

          // 각 table에 등록된 글 수를 check 합니다.
          $total = "select count(*) from $table_name[$i]";
          $result = mysql_query($total,$connect );

          $total_count = mysql_result($result, 0, "COUNT(*)") ;

          // 각 table에 등록된 글들의 합을 구합니다.
          $to = $to + $total_count ;

          // 오늘 날자에 대한 data 값을 구해 오늘 날자에 등록된 값을 구합니다.
          $current = "SELECT UNIX_TIMESTAMP(CURDATE()) as curdate";
          $result = mysql_query($current,$connect );
          $current_time = mysql_result($result, 0, "curdate") ;

          $total = "select count(*) from $table_name[$i] where date > '$current_time'";

          $result = mysql_query($total,$connect );

          $total_today = mysql_result($result, 0, "COUNT(*)") ;

          // 오늘 등록된 글들의 합을 구합니다.
          $to_today = $to_today + $total_today ;
        }

        if ( $table_name[$i] != "BoardInformation" ) {

          echo("<tr align=center bgcolor=#333333>\n" .
               "<td align=left width=30%><font id=subj>&nbsp;&nbsp;&nbsp;$table_name[$i]</font></td>\n" .
               "<td align=right width=15%>$total_today &nbsp;&nbsp;</td>\n" .
               "<td align=right width=15%>$total_count &nbsp;&nbsp;</td>\n" .
               "<form method='POST'><td width=30%>\n" .
               "<input type=button value=view onClick=fork('popup','../list.php3?table=$table_name[$i]') id=input>\n" .
               "<input type=button value=conf onClick=fork('popup','./user_admin/uadmin.php3?db=$table_name[$i]') id=input>\n" .
               "</td></form>\n" .
               "<form name='delete_db' method='post' action='act.php3'>\n" .
               "<td width=10%>\n" .
               "<input type='hidden' name='table_name' value='$table_name[$i]'>\n" .
               "<input type='hidden' name='mode' value='db_del'>\n" .
               "<input type=submit value=del id=input>\n" .
               "</td></form>\n" .
               "</tr> ");

        }
	else {

          echo("<tr align=center bgcolor=#333333>\n" .
               "<td align=left width=30%><font id=subj>&nbsp;&nbsp;&nbsp;$table_name[$i]</font></td>\n" .
               "<td align=center colspan=3>JSBoard Management Information Table</td>\n" .
               "<td align=center><img src=./img/blank.gif width=1 height=20 border=0></td>\n" .
               "</td></tr> ");

        }

      }

    }

  }
  else {

    echo("<tr align=center bgcolor=#333333>\n" .
         "<td colspan=5 align=center><br>$nodb<br>&nbsp;</td>\n" .
         "</tr>") ;

  }


  echo ("" .
   "<tr align=center bgcolor=#555555>\n" .
  "<td><font color=white id=subj>TOTAL</font></td>\n" .
  "<td align=right><font color=white>$to_today &nbsp;&nbsp;</font></td>\n" .
  "<td align=right><font color=white>$to &nbsp;&nbsp;</font></td>\n" .
  "<td colspan=2 bgcolor=#333333>\n" .
  "<form><input type=button value=\"Admin info\" onClick=fork('popup1','admin_info.php3') id=input>\n" .
  "<input type=button value=\"logout\" onClick=logout() id=input>\n" .
  "</td></form>\n" .
  "</tr>\n" .
  "\n" .
  "<tr bgcolor=#333333><form name='create_db' method='post' action='act.php3'>\n" .
  "<td colspan=3>&nbsp;&nbsp;<font color=white>$create_ment :</font>\n" .
  "<input type=text name='new_table' size=20 id=input>\n" .
  "<input type='submit' name='submit' value='$regi_bu' id=input>\n" .
  "<input type='hidden' name='mode' value='db_create'>\n" .
  "</td></form>\n" .

  "<td colspan=2  rowspan=2 align=center><font color=white>\n" .
  "<a href=http://www.oops.org><font id=td>OOPS Development<br>\n" .
  "Organization</font></a>\n" .
  "</td>\n" .
  "</tr>\n" .

  "<tr bgcolor=#333333><form name='del_db' method='post' action='act.php3'>\n" .
  "<td colspan=3>&nbsp;&nbsp;<font color=white>$delete_ment :</font>\n" .
  "<input type=text name='table_name' size=20 id=input>\n" .
  "<input type='submit' name='submit' value='$del_bu' id=input>\n" .
  "<input type='hidden' name='mode' value='db_del'>\n" .
  "</td></form>\n" .
  "</tr>\n" .


  "<tr>\n" .
  "<td colspan=5 align=center bgcolor=#555555>
  ");


  $total  = $tbl_num ;
  $lastpage_check = $total%$scale ;
  

  if ($lastpage_check == "0") { 
    $lastpage = $total/$scale ; 
    $lastpage = explode(".",$lastpage) ;
    $lastpage = $lastpage[0] ;
  }
  else { 
    $lastpage = $total/$scale+1 ; 
    $lastpage = explode(".",$lastpage) ;
    $lastpage = $lastpage[0] ;
  }

  $lastpage_basic = strlen($lastpage) ;
  $lastpage_basic1 = $lastpage_basic-1 ;
  for ($i=0; $i<$lastpage_basic ; $i++) {
    if ($i == $lastpage_basic1) {
      $lastpage_basic = $lastpage[$i];
      }
  }

  if ($lastpage_basic == 1 || $lastpage_basic == 6) {

    $last_scale_lastpage = $lastpage ;
    $last_page_num = $last_scale_lastpage-5 ;

  }
  else if ($lastpage_basic == 2 || $lastpage_basic == 7) {

    $last_scale_lastpage = $lastpage+4 ;
    $last_page_num = $last_scale_lastpage-5 ;

  }
  else if ($lastpage_basic == 3 || $lastpage_basic == 8) {

    $last_scale_lastpage = $lastpage+3 ;
    $last_page_num = $last_scale_lastpage-5 ;

  }
  else if ($lastpage_basic == 4 || $lastpage_basic == 9) {

    $last_scale_lastpage = $lastpage+2 ;
    $last_page_num = $last_scale_lastpage-5 ;

  }
  else if ($lastpage_basic == 5 || $lastpage_basic == 0) {

    $last_scale_lastpage = $lastpage+1 ;
    $last_page_num = $last_scale_lastpage-5 ;

  }


  if(!$page || $page == 1) { $page_num = 1 ; $scale_lastpage = $page_num+5 ; }

  $foo = $page/5 ;
  $foo = explode(".",$foo) ;
  $pfoo = $foo[1] ;
  $nfoo = $foo[0] ;

  if ($page > 6 && $foo == 0) {
    $page_num = $page ;
  }

  if ($total == 0) { echo "&nbsp;" ; }
  else {

    if ($page < 2) { echo "&nbsp;" ; }
    else {
      echo "<a href=$PHP_SELF><img src=./img/first.gif border=0 alt=\"$mv_first_ment\"></a>" ;
    }

    if ($page >= $lastpage) { echo "&nbsp;" ; }
    else {
      echo "<a href=$PHP_SELF?page=$lastpage&page_num=$last_page_num&scale_lastpage=$last_scale_lastpage><img src=./img/last.gif border=0 alt=\"$mv_last_ment\"> </a>" ;
    }

    for($i=$page_num ; $i<$scale_lastpage ; $i++) {

      if ($i <= $lastpage) { 

	$page_view = $i ;
	if ($i == $nowpage) { echo "<font color=red>$page_view</font> " ; }
	else  { echo "<a href=$PHP_SELF?page=$i&page_num=$page_num&scale_lastpage=$scale_lastpage><font color=white>$page_view</font></a> " ; }

      }

    }

    $p_page_num = $page_num-5 ;
    $n_page_num = $page_num+5 ;
    $p_scale_lastpage = $scale_lastpage-5 ;
    $n_scale_lastpage = $scale_lastpage+5 ;


    if ($page < 2) { echo "&nbsp;" ; }
    else {

      if ($page > 5 && $pfoo == 2) {
        echo "<a href=$PHP_SELF?page=$priv&page_num=$p_page_num&scale_lastpage=$p_scale_lastpage alt=\"$mv_priv_ment\"><font id=am>◁</font></a>" ;
      }
      else {
        echo "<a href=$PHP_SELF?page=$priv&page_num=$page_num&scale_lastpage=$scale_lastpage alt=\"$mv_priv_ment\"><font id=am>◁</font></a>" ;
      }
    }

    if ($lastpage-$page <= 0) { echo "&nbsp;" ; }
    else {
      if ($page >= 5 && $pfoo == 0) {
        echo "<a href=$PHP_SELF?page=$next&page_num=$n_page_num&scale_lastpage=$n_scale_lastpage alt=\"$mv_next_ment\"><font id=am>▷</font></a>" ;
      }
      else {
        echo "<a href=$PHP_SELF?page=$next&page_num=$page_num&scale_lastpage=$scale_lastpage alt=\"$mv_next_ment\"><font id=am>▷</font></a>" ;
      }
    }
  }

  echo ("</td>\n" .
        "</tr></table>
        ");

}

mysql_close();

include("./include/html_tail.ph") ;

?>
