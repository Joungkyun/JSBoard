<?php
########################################################################
# JSBoard Pre List v2.1.0
# Scripted By JoungKyun Kim 2005.06.20
# $Id: prelist.php,v 1.5 2009-11-16 21:52:47 oops Exp $
########################################################################

isset ($_prlist_init) || $_prlist_init = 0;

if ( $_prlist_init === 0 ) {
  echo "<script type=\"text/javascript\" src=\"{$prlist['wpath']}/theme/common/lib.js\"></script>\n" .
       "<div id=\"overDiv\" style=\"position: absolute; z-index: 50; width: 260px; visibility: hidden;\"></div>\n" .
       "<script type=\"text/javascript\" src=\"{$prlist['wpath']}/theme/common/preview.js\"></script>\n";
}

$_prlist_init++;

$prcode = isset ($prlist['code']) ? $prlist['code'] : 'en';
putenv ("JSLANG={$prcode}");

require_once "{$prlist['path']}/language/lang.php";
include_once "{$prlist['path']}/include/variable.php";

include_once "{$prlist['path']}/config/global.php";
include_once "{$prlist['path']}/include/error.php";
include_once "{$prlist['path']}/include/parse.php";
include_once "{$prlist['path']}/include/check.php";
include_once "{$prlist['path']}/database/db.php";
include_once "{$prlist['path']}/include/get.php";
include_once "{$prlist['path']}/include/print.php";

# 글리스트들을 출력하는 design
#   echo 문의 "" 사이에서 디자인을 넣으면 됨
#   단 주의 할것은 따옴표(")는 \" 로 표기를 해야 함
#   $p['link']  -> 글 리스트의 링크
#   $p['name']  -> 글쓴이
#   $p['date']  -> 등록일
#   $p['count'] -> 조회수
#
# table tag를 사용하기 위해서는 아래의 prelist() 함수를
# 잘 연계해야함
#
# $prlistTemplate 라는 변수가 정의 되어 있을 경우에는 이 변수의
# 디자인을 이용하여 출력
#
function print_prlist($p) {
  $temp = trim($GLOBALS['prlistTemplate']) ? $GLOBALS['prlistTemplate'] : "";
  if ($temp) {
    $src[] = "/P_SUBJECT_/i";
    $des[] = $p['link']; 
    $src[] = "/P_NAME_/i";
    $des[] = $p['name'];
    $src[] = "/P_DATE_/i";
    $des[] = $p['date'];
    $src[] = "/P_EMAIL_/i";
    $des[] = $p['email'];
    $src[] = "/P_REFER_/i";
    $des[] = $p['count'];
    if ($p['email']) {
      $src[] = "/P_LNAME_/i";
      $des[] = "<a href=\"mailto:{$p['email']}\">{$p['name']}</a>";
    } else {
      $src[] = "/P_LNAME_/i";
      $des[] = $p['name'];
    }

    echo preg_replace($src,$des,$temp)."\n";
  } else {
    echo "{$p['link']} {$p['name']} {$p['date']} {$p['count']}<br>\n";
  }
}

# PHP에 대해서 잘 모르신다고 생각하시는 분들은 건드리지 말것!!!
# table 이름
# $limit 글수
# $cut 출력글자수
#
function prelist($t,$limit=3,$cut=30) {
  global $prlist, $db;

  $_pvc = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);
  $GLOBALS['_pvc'] = $_pvc;

  $_limit = compatible_limit (0, $limit);
  $sql = "SELECT * FROM $t ORDER BY date DESC $_limit";
  $result = sql_query($sql, $_pvc);

  while ( $row = sql_fetch_array ($result) ) {
    $p['no'] = $row['no'];
    $p['title'] = $row['title'];

    $p['name'] = $row['name'];
    $p['date'] = date("y.m.d",$row['date']);
    $p['email'] = $row['email'];
    $p['count'] = $row['refer'];
    if ( $GLOBALS['prlistOpt'] )	
      $p['l'] = " ".$GLOBALS['prlistOpt'];

    $p['preview'] = cut_string(htmlspecialchars($row['text']),100);
    $p['preview'] = preg_replace_callback ('/[#\'\x5c]/','escape_callback',$p['preview']);
    $p['preview'] = htmlspecialchars(htmlspecialchars($p['preview']));
    $p['preview'] = preg_replace("/\r?\n/i","<BR>",$p['preview']);
    $p['preview'] = trim(str_replace("&amp;amp;","&amp;",$p['preview']));
    $p['preview'] = " onMouseOver=\"drs('{$p['preview']}'); return true;\" onMouseOut=\"nd(); return true;\"";

    if($cut) {
      if(strlen($p['title']) > $cut)
        { $p['title'] = cut_string($p['title'],$cut).".."; }
    }

    $p['link'] = "<a href=\"{$prlist['wpath']}/read.php?table=$t&amp;no={$p['no']}{$p['l']}\" {$p['preview']}>{$p['title']}</a>";

    #리스트 출력
    print_prlist($p);
  }

  sql_free_result($result);
  sql_close($_pvc);
}

function escape_callback ($matches) {
  return '&#x' . strtoupper (dechex (ord ($matches[0]))) . ';';
}
?>
