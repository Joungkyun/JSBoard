<?php
# login 정보를 얻어오는 함수
#
function get_authinfo($id,$nocry='') {
  global $edb, $db;
  if(preg_match("/user_admin/i",$_SERVER['PHP_SELF'])) { $path = "../.."; }
  elseif(preg_match("/admin/i",$_SERVER['PHP_SELF'])) { $path = ".."; }
  else { $path = "."; }

  if($edb['uses'] || $_SESSION[$jsboard]['external']) {
    $connect = sql_connect($edb['server'],$edb['user'],$edb['pass']);    

    if($edb['sql']) $sql = $edb['sql'];
    else
      $sql = "SELECT {$edb['userid']} AS nid,{$edb['username']} AS name,{$edb['useremail']} AS email,
                   {$edb['userurl']} AS url,{$edb['userpasswd']} AS passwd
              FROM {$edb['table']} WHERE {$edb['userid']} = '$id'";

    $result = sql_db_query($edb['name'],$sql,$connect);
    $r = sql_fetch_array($result);
    sql_free_result($result);
    mysql_close($connect);

    if(is_array($r)) {
      if($edb['crypts'] && !$nocry && $r['passwd']) $r['passwd'] = crypt($r['passwd']);
    }

    sql_connect($db['server'], $db['user'], $db['pass']);
    sql_select_db($db['name']);
  } else {
    $sql = "SELECT no,nid,name,email,url,passwd,position
              FROM userdb WHERE nid = '$id'";

    $result = sql_query($sql);
    $r = sql_fetch_array($result);

    sql_free_result($result);
  }

  return $r;
}


# 웹 서버 접속자의 IP 주소 혹은 도메인명을 가져오는 함수
# HTTP_X_FORWARDED_FOR - proxy server가 설정하는 환경 변수
# gethostbyaddr - IP 주소와 일치하는 호스트명을 가져옴
#                 http://www.php.net/manual/function.gethostbyaddr.php
function get_hostname($reverse=0, $host=0)
{
  if(!$host) {
    if($_SERVER['HTTP_VIA']) {
      $tmp = array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_COMING_FROM',
                   'HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED',
                   'HTTP_COMING_FROM','HTTP_PROXY','HTTP_SP_HOST');
      foreach($tmp AS $v) if($_SERVER[$v] && $_SERVER[$v] != $_SERVER['REMOTE_ADDR']) break;
      if($_SERVER[$v]) $host = preg_replace(array('/unknown,/i','/,.*/'),'',$_SERVER[$v]);
      $host = ($host = trim($host)) ? $host : $_SERVER['REMOTE_ADDR'];
    }
    else $host = $_SERVER['REMOTE_ADDR'];
  }
  $check = $reverse ? @gethostbyaddr($host) : '';

  return $check ? $check : $host;
}


# 접속한 사람이 사용하는 브라우져를 알기 위해 사용되는 함수, 현재는 FORM
# 입력창의 크기가 브라우져마다 틀리게 설정되는 것을 보정하기 위해 사용됨
#
function get_agent() {
  $agent_env = $_SERVER['HTTP_USER_AGENT'];

  # $agent 배열 정보 [br] 브라우져 종류
  #                  [os] 운영체제
  #                  [ln] 언어 (넷스케이프)
  #                  [vr] 브라우져 버젼
  #                  [co] 예외 정보
  if(preg_match("/MSIE/", $agent_env)) {
    $agent['br'] = "MSIE";
    # OS 별 구분
    if(preg_match("/NT/", $agent_env)) $agent['os'] = "NT";
    else if(preg_match("/Win/", $agent_env)) $agent['os'] = "WIN";
    else $agent['os'] = "OTHER";
    # version 정보
    $agent['vr'] = trim(preg_replace("/Mo.+MSIE ([^;]+);.+/i","\\1",$agent_env));
    $agent['vr'] = preg_replace("/[a-z]/i","",$agent['vr']);
  } else if(preg_match("/Gecko|Galeon/i",$agent_env) && !preg_match("/Netscape/i",$agent_env)) {
    $agent['br'] = preg_match ("/Firefox/i", $agent_env) ? 'Firefox' : 'MOZL';
    # client OS 구분
    if(preg_match("/NT/", $agent_env)) $agent['os'] = "NT";
    else if(preg_match("/Win/", $agent_env)) $agent['os'] = "WIN";
    else if(preg_match("/Linux/", $agent_env)) $agent['os'] = "LINUX";
    else $agent['os'] = "OTHER";
    # 언어팩
    if(preg_match("/en-US/i",$agent_env)) $agent['ln'] = "EN";
    elseif(preg_match("/ko-KR/i",$agent_env)) $agent['ln'] = "KO";
    else $agent['ln'] = "OTHER";
    # version 정보
    if ( $agent['br'] == 'Firefox' ) {
      $agent['vr'] = preg_replace("/.*Firefox\/([0-9.]+).*/i", "\\1", $agent_env);
    } else {
      $agent['vr'] = preg_replace("/Mozi[^(]+\([^;]+;[^;]+;[^;]+;[^;]+;([^)]+)\).*/i","\\1",$agent_env);
      $agent['vr'] = trim(str_replace("rv:","",$agent['vr']));
    }
    # NS 와의 공통 정보
    $agent['co'] = "mozilla";
    $agent['nco'] = "moz";
  } else if(preg_match("/Konqueror/",$agent_env)) {
    $agent['br'] = "KONQ";
  } else if(preg_match("/Lynx/", $agent_env)) {
    $agent['br'] = "LYNX";
  } else if(preg_match("/w3m/i", $agent_env)) {
    $agent['br'] = "W3M";
  } else if(preg_match("/links/i", $agent_env)) {
    $agent['br'] = "LINKS";
  } else if(preg_match("/^Mozilla/", $agent_env)) {
    $agent['br'] = "NS";
    # client OS 구분
    if(preg_match("/NT/", $agent_env)) {
      $agent['os'] = "NT";
      if(preg_match("/\[ko\]/", $agent_env)) $agent['ln'] = "KO";
    } else if(preg_match("/Win/", $agent_env)) {
      $agent['os'] = "WIN";
      if(preg_match("/\[ko\]/", $agent_env)) $agent['ln'] = "KO";
    } else if(preg_match("/Linux/", $agent_env)) {
      $agent['os'] = "LINUX";
      if(preg_match("/\[ko\]/", $agent_env)) $agent['ln'] = "KO";
    } else $agent['os'] = "OTHER";
    # version 정보
    if(preg_match("/Gecko/i",$agent_env)) $agent['vr'] = "6";
    else $agent['vr'] = "4";
    # Mozilla 와의 공통 정보
    $agent['co'] = "mozilla";

	if ( $agent['vr'] == 6 ) $agent['nco'] = 'moz';
  } else $agent['br'] = "OTHER";

  return $agent;
}

# 오늘 자정을 기준으로 UNIX_TIMESTAMP의 형태로 시각을 뽑아오는 함수
#
# date    - UNIX TIMESTAMP를 지역 시간에 맞게 지정한 형식으로 출력
#           http://www.php.net/manual/function.date.php
# mktime  - 지정한 시각의 UNIX TIMESTAMP를 가져옴
#           http://www.php.net/manual/function.mktime.php
#
function get_date() {
  $today = mktime(date("H")-12,0,0);
  return $today;
}

# 기본적인 게시판의 정보를 가져오는 함수
function get_board_info($table) {
  global $o;

  # 오늘 자정의 UNIX_TIMESTAMP를 구해옴
  $today  = get_date();

  # date 필드를 비교해서 오늘 올라온 글의 갯수를 가져옴
  $sql    = search2sql($o);
  $result = sql_query("SELECT COUNT(1/(date > '$today')), COUNT(*) FROM $table $sql");
  $A = sql_fetch_array($result);

  $count['all']    = $A[1];	# 전체 글 수
  $count['today']  = $A[0];	# 오늘 글 수

  return $count;
}

# 게시판의 전체 페이지 수를 구하는 함수
function get_page_info($count, $page = 0) {
    global $board; # 게시판 기본 설정 (config/global.php)

    # 보통 글 수를 페이지 당 글 수로 나누어 전체 페이지를 구함
    # 나눈 값은 정수형으로 변환하며 정확히 나누어 떨어지지 않으면 1을 더함
    if($count['all'] % $board['perno'])
	$pages['all'] = intval($count['all'] / $board['perno']) + 1;
    else
	$pages['all'] = intval($count['all'] / $board['perno']);

    # $page 값이 있으면 그 값을 $pages['cur'] 값으로 대입함
    if($page)
	$pages['cur'] = $page;

    # $pages['cur'] 값이 없으면 1로 대입함
    if(!$pages['cur'])
	$pages['cur'] = 1;
    # $pages['cur'] 값이 전체 페이지 수보다 클 경우 전체 페이지 값을 대입함
    if($pages['cur'] > $pages['all'])
	$pages['cur'] = $pages['all'];

    # $pages['no'] 값이 없으면 $pages['cur'] 값을 참고하여 대입함. 목록에서
    # 불러올 글의 시작 번호로 사용됨
    if(!$pages['no'])
	$pages['no'] = ($pages['cur'] - 1) * $board['perno'];

    # $pages['cur'] 값에 따라 이전(pre), 다음(nex) 페이지 값을 대입함
    if($pages['cur'] > 1)
	$pages['pre'] = $pages['cur'] - 1;
    if($pages['cur'] < $pages['all'])
	$pages['nex'] = $pages['cur'] + 1;

    return $pages;
}

# 글이 글 목록의 어느 페이지에 있는 글인지 알아내기 위한 함수
#
# intval - 변수를 정수형으로 변환함
#          http://www.php.net/manual/function.intval.php
function get_current_page($table, $idx) {
  global $board; # 게시판 기본 설정 (config/global.php)
  global $o;

  $sql = search2sql($o, 0);
  $count = get_board_info($table);

  # 지정된 글의 idx보다 큰 번호를 가진 글의 갯수를 가져옴
  $result     = sql_query("SELECT COUNT(*) FROM $table WHERE idx > '$idx' $sql");
  $count['cur'] = sql_result($result, 0, "COUNT(*)");
  sql_free_result($result);

  # 가져온 값을 페이지 당 글 수로 나누어 몇 번째 페이지인지 가져옴
  # (페이지는 1부터 시작하기 때문에 1을 더함)
  $page   = intval($count['cur'] / $board['perno']) + 1;

  return $page;
}

# 지정한 글의 다음, 이전글을 가져오는 함수
function get_pos($table, $idx) {
    global $o;

    $sql    = search2sql($o, 0);
    
    $idxdp = $idx + 1;
    $idxdm = $idx - 1;
    $idxplus = $idx + 10;
    $idxminus = $idx - 10;

    # 지정된 글의 idx보다 작은 번호를 가진 글 중에 idx가 가장 큰 글 (다음글)
    #$result    = sql_query("SELECT MAX(idx) AS idx FROM $table WHERE idx < '$idx' $sql");
    $result    = sql_query("SELECT MAX(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxminus' AND '$idxdm') $sql");
    $pos['next'] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos['next']) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = '{$pos['next']}'");
	$next   = sql_fetch_array($result);
	sql_free_result($result);
        $next['title'] = str_replace("&amp;","&",$next['title']);
	$next['title'] = preg_replace("/(#|')/","\\\\1",htmlspecialchars($next['title']));

	$pos['next'] = $next['no'];
	if($next['reto']) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = '{$next['reto']}'");
	    $next['num'] = sql_result($result, 0, "num");
	    sql_free_result($result);
	    $pos['next_t'] = "Reply of No.{$next['num']}: {$next['title']}";
	} else {
	    $pos['next_t'] = "No.{$next['num']}: {$next['title']}";
	}
    }

    # 지정된 글의 idx보다 큰 번호를 가진 글 중에 idx가 가장 작은 글 (이전글)
    #$result    = sql_query("SELECT MIN(idx) AS idx FROM $table WHERE idx > '$idx' $sql");
    $result    = sql_query("SELECT MIN(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxdp' AND '$idxplus') $sql");
    $pos['prev'] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos['prev']) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = '{$pos['prev']}'");
	$prev   = sql_fetch_array($result);
	sql_free_result($result);
        $prev['title'] = str_replace("&amp;","&",$prev['title']);
	$prev['title'] = preg_replace("/(#|')/","\\\\1",htmlspecialchars($prev['title']));

	$pos['prev'] = $prev['no'];
	if($prev['reto']) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = '{$prev['reto']}'");
	    $prev['num'] = sql_result($result, 0, "num");
	    sql_free_result($result);
	    $pos['prev_t'] = "Reply of No.{$prev['num']}: {$prev['title']}";
	} else {
	    $pos['prev_t'] = "No.{$prev['num']}: {$prev['title']}";
	}
    }

    return $pos;
}

# PHP의 microtime 함수로 얻어지는 값을 비교하여 경과 시간을 가져오는 함수
#
# explode - 구분 문자열을 기준으로 문자열을 나눔
#           http://www.php.net/manual/function.explode.php
function get_microtime($old, $new) {
  $start = explode(" ", $old);
  $end = explode(" ", $new);

  return sprintf("%.2f", ($end[1] + $end[0]) - ($start[1] + $start[0]));
}
    
# 알맞은 제목을 가져오기 위해 사용됨 (html/head.php)
#
# basename - 파일 경로에서 파일명만을 가져옴
#            http://www.php.net/manual/function.basename.php
function get_title() {
  global $board, $langs; # 게시판 기본 설정 (config/global.php)

  $title  = $board['title'];

  # SCRIPT_NAME이라는 아파치 환경 변수를 가져옴 (현재 PHP 파일)
  $script = $_SERVER['SCRIPT_NAME'];
  $script = basename($script);

  switch($script) {
    case "list.php":
      $title .= " {$langs['get_v']}";
      break;
    case "read.php":
      $title .= " {$langs['get_r']}";
      break;
    case "edit.php":
      $title .= " {$langs['get_e']}";
      break;
    case "write.php":
      $title .= " {$langs['get_w']}";
      break;
    case "reply.php":
      $title .= " {$langs['get_re']}";
      break;
    case "delete.php":
      $title .= " {$langs['get_d']}";
      break;
    case "user.php":
      $title .= " {$langs['get_u']}";
      break;
    case "regist.php":
      $title .= " {$langs['get_rg']}";
      break;
  }

  return $title;
}

function get_article($table, $no, $field0 = "*", $field1 = "no") {
  global $langs;
  if(!$no)
    print_error($langs['get_no'],250,150,1);

  $result  = sql_query("SELECT $field0 FROM $table WHERE $field1 = '$no'");
  $article = sql_fetch_array($result);
  sql_free_result($result);

  if(!$article)
    print_error($langs['get_n'],250,150,1);

  return $article;
}

# 파일 크기 출력 함수 by 김칠봉 <san2@linuxchannel.net>
# $bfsize 변수는 bytes 단위의 크기임
#
# number_formant() - 3자리를 기준으로 컴마를 사용
function human_fsize($bfsize, $sub = "0") {
  $BYTES = number_format($bfsize) . " Bytes"; // 3자리를 기준으로 컴마

  if($bfsize < 1024) return $BYTES; # Bytes 범위
  elseif($bfsize < 1048576) $bfsize = number_format($bfsize/1024,1) . " KB"; # KBytes 범위
  elseif($bfsize < 1073741827) $bfsize = number_format($bfsize/1048576,1) . " MB"; # MB 범위
  else $bfsize = number_format($bfsize/1073741827,1) . " GB"; # GB 범위

  if($sub) $bfsize .= "($BYTES)";

  return $bfsize;
} 

function viewfile($tail) {
  global $board, $table, $list, $upload;
  global $langs, $icons, $agent;

  $upload_file = "./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}";
  $wupload_file = "./data/$table/{$upload['dir']}/{$list['bcfile']}/".urlencode($list['bofile']);

  $source1 = "<p><br>\n---- {$list['bofile']} {$langs['inc_file']} -------------------------- \n<p>\n<pre>\n";
  $source2 = "\n</pre>\n<br><br>";
  $source3 = "   <font color=\"#ff0000\">{$list['bofile']}</font> file is broken link!!\n\n";

  if (@file_exists($upload_file)) {
    if ($agent['br'] == "MSIE" && $agent['vr'] >= 6)
      $bmpchk = "|bmp";

    if (preg_match("/^(gif|jpg|png{$bmpchk})$/i",$tail)) {
      $imginfo = GetImageSize($upload_file);
      if($agent['co'] == "mozilla") $list['bofile'] = urlencode($list['bofile']);
      $uplink_file = "./form.php?mode=photo&amp;table=$table&amp;f[c]={$list['bcfile']}&amp;f[n]={$list['bofile']}&amp;f[w]={$imginfo[0]}&amp;f[h]={$imginfo[1]}";
      if($imginfo[0] > $board['width'] - 6 && !preg_match("/%/",$board['width'])) {
        $p['vars'] = $imginfo[0]/$board['width'];
        $p['width'] = $board['width'] - 6;
        $p['height'] = intval($imginfo[1]/$p['vars']);

        if(extension_loaded("gd") && $tail != "gif" && $tail != "bmp") {
          $ImgUrl = rawurlencode("$wupload_file");
          $ImgPath = "<IMG SRC=\"./image.php?path=$ImgUrl&amp;width={$p['width']}&amp;height={$p['height']}\" WIDTH={$p['width']} HEIGHT={$p['height']} BORDER=0 ALT=''>";
        } else
          $ImgPath = "<IMG SRC=\"$wupload_file\" WIDTH={$p['width']} HEIGHT={$p['height']} BORDER=0 ALT=''>";

        $p['up']  = "[ <B>Original Size</B> $imginfo[0] * $imginfo[1] ]<br>\n";
        $p['up'] .= "<A HREF=\"javascript:new_windows('$uplink_file','photo',0,0,$imginfo[0],$imginfo[1])\">$ImgPath</A>\n<P>\n";
      } else {
        $p['up'] = "<IMG SRC=\"$wupload_file\" $imginfo[3] BORDER=0 ALT=''>\n<p>\n";
      }
    } else if (preg_match("/^(phps|txt|html?|shs)$/i",$tail)) {
      $view = file_operate($upload_file,"r",0,1200);
      $view = htmlspecialchars(cut_string($view,1000));
      if (filesize($upload_file) > 1000) $view = $view . " <p>\n ......{$langs['preview']}\n\n";

      $p['down'] = "$source1$view$source2";
    } elseif (preg_match("/^(mid|wav|mp3)$/i",$tail)) {
      if($tail == "mp3" && $agent['co'] == "mozilla")
        $p['up'] = "[ MP3 file은 IE에서만 들으실수 있습니다. ]";
      elseif($agent['br'] == "LYNX")
        $p['bo'] = "";
      else
        $p['bo'] = "<embed src=\"$upload_file\" autostart=\"true\" hidden=\"true\" mastersound>";
    } elseif (preg_match("/^(mpeg|mpg|asf|dat|avi|wmv)$/i",$tail)) {
      if($agent['br'] == "MSIE") $p['up'] = "<embed src=\"$upload_file\" autostart=\"true\">";
    } elseif ($tail == "mov" && $agent['br'] == "MSIE") {
      $p['up'] = "<embed src=\"$upload_file\" autostart=\"true\" width=300 height=300 align=\"center\">";
    } elseif ($tail == "swf") {
      $flash_size = $board['width'] - 10;
      if($agent['br'] == "MSIE" || $agent['nco'] == "moz")
        $p['up'] = "<embed src=\"$upload_file\" width=\"$flash_size\" height=\"$flash_size\" align=\"center\">";
    }
  } else $p['down'] = "$source1$source3$source2";

  return $p;
}

# 파일을 변수로 받고 쓰는 함수
# p -> 파일 경로
# m -> 파일 작동 모드(r-읽기,w-쓰기,a-파일끝부터 쓰기)
# msg -> 실패시 에러 메세지
# s -> 쓰기모드에서는 쓸내용
# t -> 읽기모드에서 사이즈 만큼 받을 것인지 아니면 배열로 파일
#      전체를 받을 것인지 결정
#
function file_operate($p,$m,$msg='',$s='',$t=0) {
  if($m == "r" || $m == "w" || $m == "a") {
    $m .= "b";
    
    # file point 를 open
    if(!$t && $f=@fopen($p,$m)) {
      if(check_windows()) {
        $src = array("/\n/i","/\r*\n/i");
        $tar = array("\r\n","\r\n");
      } else {
        $src = array("/^M/i","/\r\n/i");
        $tar = array("","\n");
      }
      $s = preg_replace($src,$tar,$s);
      if($m != "rb") @fwrite($f,$s);
      else $var = @fread($f,filesize($p));
      @fclose($f);
    }
    elseif ($t && $m == "rb" && @file_exists($p)) $var = file($p);
    else { if(trim($msg)) print_error($msg,250,150,1); }
  }

  if($m == "rb") return $var;
}

# http socket 으로 연결을 하여 html source 를 가져오는 함수
# 비고 : HTTP/1.1 지원 가능
#
# $url -> 해당 서버의 주소 (http:// 는 생략)
# $size -> 해당 문서의 size
# $file -> 행당 문서의 URI
# $type -> socket(1) 방식 또는 fopen(null)
function get_html_src($url,$size=5000,$file="",$type="") {
  if(!$type) {
    $p = @fsockopen($url,80,$errno,$errstr);
    fputs($p,"GET /$file HTTP/1.1\r\nhost: $url\r\n\r\n");
  } else $p = @fopen("http://$url/$file","rb");
  $f = fread($p,$size);
  fclose($p);

  if(!$type) {
    $s = explode("\n",$f);
    return $s;
  } else return $f;
}

# 스팸 등록기 체크를 위한 알고리즘
#
function get_spam_value($v) {
  $chk = explode(":",$v);
  $ran = preg_replace("/[^1-9]/i","",$_COOKIE['PHPSESSID']);
  $ran = ($ran > 99999) ? substr($ran,0,5) : $ran;
  $ret = $chk[0] * $ran - ($chk[1] * $chk[2]);

  return $ret;
}

# upload 관련 변수들을 조정
#
function get_upload_value($up) {
  if($up['yesno']) {
    if($up['maxtime']) set_time_limit($up['maxtime']);
    # JSBoard 에서 조정할 수 있는 업로드 최대 사이즈
    # 최대값은 POST 데이타를 위해 post_max_size 보다 1M 를 작게 잡는다.
    $max = ini_get(post_max_size);
    if(preg_match("/M$/i",$max)) {
      $max = (preg_replace("/M$/i","",$max) - 1) * 1024 * 1024;
    } elseif (preg_match("/K$/i",$max)) {
      $max = (preg_replace("/K$/i","",$max) - 1) * 1024;
    } else {
      $max -= 1024;
    }
    ini_set(upload_max_filesize,$max);
    $size = ($up['maxsize'] > $max) ? $max : $up['maxsize'];

    return $size;
  } else return 0;
}
?>
