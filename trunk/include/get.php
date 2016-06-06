<?php
# $Id: get.php,v 1.12 2009-11-16 21:52:47 oops Exp $

# login 정보를 얻어오는 함수
#
function get_authinfo ($id, $nocry='') {
  global $edb, $db, $c;

  if ( preg_match ("/user_admin/i", $_SERVER['PHP_SELF']) ) {
    $path = "../..";
  } elseif ( preg_match ("/admin/i", $_SERVER['PHP_SELF']) ) {
    $path = "..";
  } else {
    $path = ".";
  }

  if ( $edb['uses'] || $_SESSION[$jsboard]['external'] ) {
    $c = sql_connect($edb['server'], $edb['user'], $edb['pass'], $edb['name']);

    if ( $edb['sql'] ) $sql = $edb['sql'];
    else
      $sql = "SELECT {$edb['userid']} AS nid,{$edb['username']} AS name,{$edb['useremail']} AS email,
                   {$edb['userurl']} AS url,{$edb['userpasswd']} AS passwd, position
              FROM {$edb['table']} WHERE {$edb['userid']} = '{$id}'";

    $_r = sql_query ($sql, $c);
    $r = sql_fetch_array ($_r);
    sql_free_result ($_r);
    sql_close ($c);

    if ( is_array ($r) ) {
      if ( $edb['crypts'] && ! $nocry && $r['passwd'] )
        $r['passwd'] = crypt ($r['passwd']);
    }

    $c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);
  } else {
    $sql = "SELECT no,nid,name,email,url,passwd,position
              FROM userdb WHERE nid = '{$id}'";

    $_r = sql_query ($sql, $c);
    $r = sql_fetch_array ($_r);

    sql_free_result ($_r);
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
  if(preg_match('/MSIE/', $agent_env)) {
    $agent['br'] = 'MSIE';
    # OS 별 구분
    if(preg_match('/NT/', $agent_env)) $agent['os'] = 'NT';
    else if(preg_match('/Win/', $agent_env)) $agent['os'] = 'WIN';
    else $agent['os'] = 'OTHER';
    # version 정보
    $agent['vr'] = trim(preg_replace('/Mo.+MSIE ([^;]+);.+/i','\\1',$agent_env));
    $agent['vr'] = preg_replace('/[a-z]/i','',$agent['vr']);
  } else if(preg_match('/Gecko|Galeon/i',$agent_env) && !preg_match('/Netscape/i',$agent_env)) {
    if ( preg_match ('/Firefox/i', $agent_env) )
      $agent['br'] = 'Firefox';
    else if ( preg_match ('/Chrome/', $agent_env) )
      $agent['br'] = 'Chrome';
    else
      $agent['br'] = 'MOZL';

    # client OS 구분
    if(preg_match('/NT/', $agent_env)) $agent['os'] = 'NT';
    else if(preg_match('/Win/', $agent_env)) $agent['os'] = 'WIN';
    else if(preg_match('/Linux/', $agent_env)) $agent['os'] = 'LINUX';
    else $agent['os'] = 'OTHER';
    # 언어팩
    if(preg_match('/en-US/i',$agent_env)) $agent['ln'] = 'EN';
    elseif(preg_match('/ko-KR/i',$agent_env)) $agent['ln'] = 'KO';
    elseif(preg_match('/ja-JP/i',$agent_env)) $agent['ln'] = 'JP';
    else $agent['ln'] = 'OTHER';
    # version 정보
    if ( $agent['br'] == 'Firefox' ) {
      $agent['vr'] = preg_replace('/.*Firefox\/([0-9.]+).*/i', '\\1', $agent_env);
    } else if ( $agent['br'] == 'Chrome' ) {
      $agent['vr'] = preg_replace('/.*Chrome\/([^ ]+).*/i', '\\1', $agent_env);
    } else {
      $agent['vr'] = preg_replace('/Mozi[^(]+\([^;]+;[^;]+;[^;]+;[^;]+;([^)]+)\).*/i','\\1',$agent_env);
      $agent['vr'] = trim(str_replace('rv:','',$agent['vr']));
    }
    # NS 와의 공통 정보
    $agent['co'] = 'mozilla';
    $agent['nco'] = 'moz';
  } else if(preg_match('/Konqueror/',$agent_env)) {
    $agent['br'] = 'KONQ';
  } else if(preg_match('/Lynx/', $agent_env)) {
    $agent['br'] = 'LYNX';
    $agent['tx'] = 1;
  } else if(preg_match('/w3m/i', $agent_env)) {
    $agent['br'] = 'W3M';
    $agent['tx'] = 1;
  } else if(preg_match('/links/i', $agent_env)) {
    $agent['br'] = 'LINKS';
    $agent['tx'] = 1;
  } else if(preg_match("/^Mozilla/", $agent_env)) {
    $agent['br'] = 'NS';
    # client OS 구분
    if(preg_match('/NT/', $agent_env)) $agent['os'] = 'NT';
    else if(preg_match('/Win/', $agent_env)) $agent['os'] = 'WIN';
    else if(preg_match('/Linux/', $agent_env)) $agent['os'] = 'LINUX';
    else $agent['os'] = 'OTHER';
    if(preg_match('/\[ko\]/', $agent_env)) $agent['ln'] = 'KO';
    # version 정보
    if(preg_match('/Gecko/i',$agent_env)) $agent['vr'] = 6;
    else $agent['vr'] = 4;
    # Mozilla 와의 공통 정보
    $agent['co'] = 'mozilla';

    if ( $agent['vr'] == 6 ) $agent['nco'] = 'moz';
  } else if(preg_match('/^Opera/', $agent_env)) {
    $agent['br'] = 'OPERA';
    # 언어 정보
    if(preg_match('/\[([^]]+)\]/', $agent_env, $_m))
      $agent['ln'] = strtoupper ($_m[1]);
    else if(preg_match('/;[ ]*([a-z]{2})\)/i', $agent_env, $_m))
      $agent['ln'] = strtoupper ($_m[1]);
    else $agent['ln'] = "OTHER";

    # OS 구분
    if (preg_match('/Windows (NT|2000)/i', $agent_env))
      $agent['os'] = 'NT';
    else if (preg_match('/Windows/i', $agent_env))
      $agent['os'] = 'WIN';
    else if (preg_match('/Linux/i', $agent_env))
      $agent['os'] = 'LINUX';
    else
      $agent['os'] = 'OTHER';

    # Mozilla 와의 공통 정보
    $agent['co'] = 'mozilla';

    # version 정보
    $agent['vr'] = preg_replace ('/^Opera\/([0-9]+(\.[0-9]+)?) .*/', '\\1', $agent_env);
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
  global $o, $c;

  # 오늘 자정의 UNIX_TIMESTAMP를 구해옴
  $today  = get_date();

  # date 필드를 비교해서 오늘 올라온 글의 갯수를 가져옴
  $sql    = search2sql($o);

  $A      = get_counter ($c, $table, $today, $sql);
  $count['all']   = $A['A'];	# 전체 글 수
  $count['today'] = $A['T'];	# 오늘 글 수

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
  global $o, $c;

  $sql = search2sql($o, 0);
  $count = get_board_info($table);

  # 지정된 글의 idx보다 큰 번호를 가진 글의 갯수를 가져옴
  $result     = sql_query("SELECT COUNT(*) as cnt FROM $table WHERE idx > '$idx' $sql", $c);
  $count['cur'] = sql_result($result, 0, 'cnt');
  sql_free_result($result);

  # 가져온 값을 페이지 당 글 수로 나누어 몇 번째 페이지인지 가져옴
  # (페이지는 1부터 시작하기 때문에 1을 더함)
  $page   = intval($count['cur'] / $board['perno']) + 1;

  return $page;
}

# 지정한 글의 다음, 이전글을 가져오는 함수
function get_pos($table, $idx) {
    global $o, $c, $db;

    $sql    = search2sql($o, 0);
    
    $idxdp    = $idx + 1;
    $idxdm    = $idx - 1;
    $idxplus  = $idx + 10;
    $idxminus = $idx - 10;

    # 지정된 글의 idx보다 작은 번호를 가진 글 중에 idx가 가장 큰 글 (다음글)
    #$query     = "SELECT MAX(idx) AS idx FROM $table WHERE idx < '$idx' $sql";
    #$result    = sql_query($query, $c);
    $query     = "SELECT MAX(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxminus' AND '$idxdm') $sql";
    $result    = sql_query($query, $c);
    $pos['next'] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if ( $pos['next'] ) { 
      $query  = "SELECT no, title, num, reto FROM $table WHERE idx = '{$pos['next']}'";
      $result = sql_query($query, $c);
      $next   = sql_fetch_array($result);
      sql_free_result($result);
      $next['title'] = str_replace("&amp;","&",$next['title']);
      $next['title'] = preg_replace("/(#|')/","\\\\1",htmlspecialchars($next['title']));

      $pos['next'] = $next['no'];
      if($next['reto']) {
        $query     = "SELECT num FROM $table WHERE no = '{$next['reto']}'";
        $result    = sql_query($query, $c);
        $next['num'] = sql_result($result, 0, "num");
        sql_free_result($result);
        $pos['next_t'] = "Reply of No.{$next['num']}: {$next['title']}";
      } else {
        $pos['next_t'] = "No.{$next['num']}: {$next['title']}";
      }
    }

    # 지정된 글의 idx보다 큰 번호를 가진 글 중에 idx가 가장 작은 글 (이전글)
    #$query     = "SELECT MIN(idx) AS idx FROM $table WHERE idx > '$idx' $sql";
    #$result    = sql_query($query, $c);
    $query     = "SELECT MIN(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxdp' AND '$idxplus') $sql";
    $result    = sql_query($query, $c);
    $pos['prev'] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos['prev']) { 
        $query  = "SELECT no, title, num, reto FROM $table WHERE idx = '{$pos['prev']}'";
        $result = sql_query($query, $c);
        $prev   = sql_fetch_array($result);
        sql_free_result($result);
        $prev['title'] = str_replace("&amp;","&",$prev['title']);
        $prev['title'] = preg_replace("/(#|')/","\\\\1",htmlspecialchars($prev['title']));

        $pos['prev'] = $prev['no'];
        if($prev['reto']) {
            $query     = "SELECT num FROM $table WHERE no = '{$prev['reto']}'";
            $result    = sql_query($query, $c);
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
  global $board, $_; # 게시판 기본 설정 (config/global.php)

  $title  = $board['title'];

  # SCRIPT_NAME이라는 아파치 환경 변수를 가져옴 (현재 PHP 파일)
  $script = $_SERVER['SCRIPT_NAME'];
  $script = basename($script);

  switch($script) {
    case "list.php":
      $title .= " " . $_('get_v');
      break;
    case "read.php":
      $title .= " " . $_('get_r');
      break;
    case "edit.php":
      $title .= " " . $_('get_e');
      break;
    case "write.php":
      $title .= " " . $_('get_w');
      break;
    case "reply.php":
      $title .= " " . $_('get_re');
      break;
    case "delete.php":
      $title .= " " . $_('get_d');
      break;
    case "user.php":
      $title .= " " . $_('get_u');
      break;
    case "regist.php":
      $title .= " " . $_('get_rg');
      break;
  }

  return $title;
}

function get_article($table, $no, $field0 = "*", $field1 = "no") {
  global $_, $c, $db;
  if(!$no)
    print_error($_('get_no'),250,150,1);

  $result  = sql_query("SELECT $field0 FROM $table WHERE $field1 = '$no'", $c);
  $article = sql_fetch_array($result);
  sql_free_result($result);

  if(!$article)
    print_error($_('get_n'),250,150,1);

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
  global $_, $icons, $agent;

  $upload_file = "./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}";
  $wupload_file = "./data/$table/{$upload['dir']}/{$list['bcfile']}/".urlencode($list['bofile']);

  $source1 = "<br>\n---- {$list['bofile']} " . $_('inc_file') . " -------------------------- \n<br>\n<pre>\n";
  $source2 = "\n</pre>\n<br><br>";
  $source3 = "   <font color=\"#ff0000\">{$list['bofile']}</font> file is broken link!!\n\n";

  if (@file_exists($upload_file)) {
    if ($agent['br'] == "MSIE" && $agent['vr'] >= 6)
      $bmpchk = "|bmp";

    if (preg_match("/^(gif|jpg|png{$bmpchk})$/i",$tail)) {
      $imginfo = GetImageSize($upload_file);
      if($agent['co'] == "mozilla") $list['bofile'] = urlencode($list['bofile']);
      $uplink_file = "./form.php?table=$table&mode=photo&f[c]={$list['bcfile']}&f[n]={$list['bofile']}&f[w]={$imginfo[0]}&f[h]={$imginfo[1]}";
      $uplink_file = htmlspecialchars ($uplink_file);
      if($imginfo[0] > $board['width'] - 6 && !preg_match("/%/",$board['width'])) {
        $p['vars'] = $imginfo[0]/$board['width'];
        $p['width'] = $board['width'] - 6;
        $p['height'] = intval($imginfo[1]/$p['vars']);

        if(extension_loaded("gd") && $tail != "gif" && $tail != "bmp") {
          $ImgUrl = rawurlencode($wupload_file);
          $ImgPath = "<img src=\"./image.php?path=$ImgUrl&amp;width={$p['width']}&amp;height={$p['height']}\" width={$p['width']} height={$p['height']} border=0 alt=\"\">";
        } else
          $ImgPath = "<img src=\"$wupload_file\" width={$p['width']} height={$p['height']} border=0 alt=\"\">";

        $p['up']  = "[ <b>Original Size</b> {$imginfo[0]} * {$imginfo[1]} ]<br>\n";
        $p['up'] .= "<a href=\"javascript:new_windows('$uplink_file','photo',0,0,$imginfo[0],$imginfo[1]);\">$ImgPath</a>\n<p>\n";
      } else {
        $p['up'] = "<img src=\"$wupload_file\" $imginfo[3] border=0 alt=\"\">\n<p>\n";
      }
    } else if (preg_match("/^(phps|txt|html?|shs)$/i",$tail)) {
      $view = readfile_r ($upload_file);
      $view = htmlspecialchars(cut_string($view,1000));
      if (filesize($upload_file) > 1000) $view = $view . " <p>\n ......" . $_('preview') . "\n\n";

      $p['down'] = "$source1$view$source2";
    } elseif (preg_match("/^(mid|wav|mp3)$/i",$tail)) {
      if($tail == 'mp3') {
        $p['up'] = <<<EOF
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
        codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0"
        width="240" height="20" id="dewplayer" align="middle">
  <param name="wmode" value="transparent" />
  <param name="allowScriptAccess" value="sameDomain" />
  <param name="movie" value="theme/player/dewplayer-vol.swf?mp3={$upload_file}&amp;autostart=1&amp;autoreplay=0&amp;showtime=1&amp;randomplay=0&amp;nopointer=0" />
  <param name="quality" value="high" />
  <param name="bgcolor" value="ffffff" />
  <embed src="theme/player/dewplayer-vol.swf?mp3={$upload_file}&amp;autostart=1&amp;autoreplay=0&amp;showtime=1&amp;randomplay=0&amp;nopointer=0"
         quality="high" bgcolor="ffffff" width="240" height="20" name="dewplayer"
         wmode="transparent" align="middle" allowScriptAccess="sameDomain"
         type="application/x-shockwave-flash"
         pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
</object>
EOF;
      } elseif($agent['tx'])
        $p['bo'] = '';
      else
        $p['bo'] = "<embed src=\"$upload_file\" autostart=\"true\" hidden=\"true\" mastersound>";
    } elseif (preg_match("/^(mpeg|mpg|asf|dat|avi|wmv)$/i",$tail)) {
      if($agent['br'] == "MSIE") $p['up'] = "<embed src=\"$upload_file\" autostart=\"true\">";
    } elseif ($tail == "mov" && $agent['br'] == "MSIE") {
      $p['up'] = "<embed src=\"$upload_file\" autostart=\"true\" width=300 height=300 align=\"center\">";
    } elseif ($tail == "swf") {
      $flash_size = $board['width'] - 10;
      if($agent['br'] == 'MSIE' || $agent['nco'] == 'moz')
        $p['up'] = "<embed src=\"$upload_file\" width=\"$flash_size\" height=\"$flash_size\" align=\"center\">";
    }
  } else $p['down'] = "$source1$source3$source2";

  return $p;
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

# upload 관련 변수들을 조정
#
function get_upload_value($up) {
  if($up['yesno']) {
    if($up['maxtime']) set_time_limit($up['maxtime']);
    # JSBoard 에서 조정할 수 있는 업로드 최대 사이즈
    # 최대값은 POST 데이타를 위해 post_max_size 보다 1M 를 작게 잡는다.
    $max = ini_get('post_max_size');
    if(preg_match("/M$/i",$max)) {
      $max = (preg_replace("/M$/i","",$max) - 1) * 1024 * 1024;
    } elseif (preg_match("/K$/i",$max)) {
      $max = (preg_replace("/K$/i","",$max) - 1) * 1024;
    } else {
      $max -= 1024;
    }
    ini_set('upload_max_filesize',$max);
    $size = ($up['maxsize'] > $max) ? $max : $up['maxsize'];

    return $size;
  } else return 0;
}

function readfile_r ($_f, $_array = 0) {
  if ( ! file_exists ($_f) )
    print_error ("$_f not found", 250, 250, 1);

  if ( $_array ) {
    $_r = @file ($_f);
    $_r = preg_replace ("/\n$/", '', $_r);
  } else {
    ob_start ();
    readfile ($_f);
    $_r = ob_get_contents ();
    ob_end_clean ();
  }

  return $_r;
}

function writefile_r ($_file, $_text, $attach = 0) {
  $_m = $attach ? 'ab' : 'wb';

  $p = fopen ($_file, $_m);

  if ( ! is_resource ($p) )
    print_error ("Can't not open {$_file}\n", 250, 250, 1);

  if ( check_windows () ) {
    $_s = array ("/\n/", "/\r*\n/");
    $_t = array ("\r\n", "\r\n");
  } else {
    $_s = array ("//", "/\r\n/");
    $_t = array ('', "\n");
  }

  $s = preg_replace ($_s, $_t, $_text);

  fwrite ($p, $s);
  fclose ($p);
}

function content_disposition ($n) {
  global $agent, $_, $_code;

  switch ($n) {
    case 'Firefox' :
      # RFC 2231
      $r = 'filename*0*' . $_code . '*' . $_('charset') . '*=' . rawurlencode ($n);
      break;
    case 'Opera' :
      if ($agent['vr'] > 6)
        $r = 'filename*0*' . $_code . '*' . $_('charset') . '*=' . rawurlencode ($n);
      else
        $r = 'filename="' . $n . '"';
      break;
    default:
      # RFC 2047
      #$r = '=?'.$_('charset').'?B?'.base64_encode($dn['name']).'?=';
      $r = 'filename="' . $n . '"';
  }

  return $r;
}
?>
