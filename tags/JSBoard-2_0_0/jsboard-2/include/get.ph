<?
# login ������ ������ �Լ�
#
function get_authinfo($id,$nocry='') {
  global $edb, $db;
  if(eregi("user_admin",$_SERVER[PHP_SELF])) { $path = "../.."; }
  elseif(eregi("admin",$_SERVER[PHP_SELF])) { $path = ".."; }
  else { $path = "."; }

  if($edb[uses] || $_SESSION[$jsboard][external]) {
    $connect = sql_connect($edb[server],$edb[user],$edb[pass]);    

    if($edb[sql]) $sql = $edb[sql];
    else
      $sql = "SELECT $edb[userid] AS nid,$edb[username] AS name,$edb[useremail] AS email,
                   $edb[userurl] AS url,$edb[userpasswd] AS passwd
              FROM $edb[table] WHERE $edb[userid] = '$id'";

    $result = sql_db_query($edb[name],$sql,$connect);
    $r = sql_fetch_array($result);
    sql_free_result($result);
    mysql_close($connect);

    if(is_array($r)) {
      if($edb[crypts] && !$nocry && $r[passwd]) $r[passwd] = crypt($r[passwd]);
    }

    sql_connect($db[server], $db[user], $db[pass]);
    sql_select_db($db[name]);
  } else {
    $sql = "SELECT no,nid,name,email,url,passwd,position
              FROM userdb WHERE nid = '$id'";

    $result = sql_query($sql);
    $r = sql_fetch_array($result);

    sql_free_result($result);
  }

  return $r;
}


# �� ���� �������� IP �ּ� Ȥ�� �����θ��� �������� �Լ�
# HTTP_X_FORWARDED_FOR - proxy server�� �����ϴ� ȯ�� ����
# gethostbyaddr - IP �ּҿ� ��ġ�ϴ� ȣ��Ʈ���� ������
#                 http://www.php.net/manual/function.gethostbyaddr.php
function get_hostname($reverse = 0,$addr = 0) {
  if(!$addr) {
    # proxy ���� �����ϴ� �� ip address �� ȯ�� ������ �����Ҷ� �� ip address �� ����
    $proxytype = array("HTTP_VIA","HTTP_X_COMING_FROM","HTTP_X_FORWARDED_FOR",
                       "HTTP_X_FORWARDED","HTTP_COMING_FROM","HTTP_FORWARDED_FOR",
                       "HTTP_FORWARDED");
                       
    for($i=0;$i<sizeof($proxytype);$i++) {
      if($_SERVER[$proxytype[$i]]) {
        $host = $_SERVER[$proxytype[$i]];
        break;
      }
    }

    # proxy�� ������ �ʰ� ���� �Ҷ� ����ġ ȯ�� ������
    # REMOTE_ADDR���� �������� IP�� ������
    $host = $host ? $host : $_SERVER[REMOTE_ADDR];
  } else $host = $addr;

  $check = $reverse ? @gethostbyaddr($host) : "";
  $host = $check ? $check : $host;

  return $host;
}

# ������ ����� ����ϴ� �������� �˱� ���� ���Ǵ� �Լ�, ����� FORM
# �Է�â�� ũ�Ⱑ ���������� Ʋ���� �����Ǵ� ���� �����ϱ� ���� ����
#
function get_agent() {
  $agent_env = $_SERVER[HTTP_USER_AGENT];

  # $agent �迭 ���� [br] ������ ����
  #                  [os] �ü��
  #                  [ln] ��� (�ݽ�������)
  #                  [vr] ������ ����
  #                  [co] ���� ����
  if(ereg("MSIE", $agent_env)) {
    $agent[br] = "MSIE";
    # OS �� ����
    if(ereg("NT", $agent_env)) $agent[os] = "NT";
    else if(ereg("Win", $agent_env)) $agent[os] = "WIN";
    else $agent[os] = "OTHER";
    # version ����
    $agent[vr] = trim(eregi_replace("Mo.+MSIE ([^;]+);.+","\\1",$agent_env));
    $agent[vr] = eregi_replace("[a-z]","",$agent[vr]);
  } else if(eregi("Gecko|Galeon",$agent_env) && !eregi("Netscape",$agent_env)) {
    $agent[br] = "MOZL";
    # client OS ����
    if(ereg("NT", $agent_env)) $agent[os] = "NT";
    else if(ereg("Win", $agent_env)) $agent[os] = "WIN";
    else if(ereg("Linux", $agent_env)) $agent[os] = "LINUX";
    else $agent[os] = "OTHER";
    # �����
    if(eregi("en-US",$agent_env)) $agent[ln] = "EN";
    elseif(eregi("ko-KR",$agent_env)) $agent[ln] = "KO";
    else $agent[ln] = "OTHER";
    # version ����
    $agent[vr] = eregi_replace("Mozi[^(]+\([^;]+;[^;]+;[^;]+;[^;]+;([^)]+)\).*","\\1",$agent_env);
    $agent[vr] = trim(str_replace("rv:","",$agent[vr]));
    # NS ���� ���� ����
    $agent[co] = "mozilla";
  } else if(ereg("Konqueror",$agent_env)) {
    $agent[br] = "KONQ";
  } else if(ereg("Lynx", $agent_env)) {
    $agent[br] = "LYNX";
  } else if(eregi("w3m", $agent_env)) {
    $agent[br] = "W3M";
  } else if(eregi("links", $agent_env)) {
    $agent[br] = "LINKS";
  } else if(ereg("^Mozilla", $agent_env)) {
    $agent[br] = "NS";
    # client OS ����
    if(ereg("NT", $agent_env)) {
      $agent[os] = "NT";
      if(ereg("\[ko\]", $agent_env)) $agent[ln] = "KO";
    } else if(ereg("Win", $agent_env)) {
      $agent[os] = "WIN";
      if(ereg("\[ko\]", $agent_env)) $agent[ln] = "KO";
    } else if(ereg("Linux", $agent_env)) {
      $agent[os] = "LINUX";
      if(ereg("\[ko\]", $agent_env)) $agent[ln] = "KO";
    } else $agent[os] = "OTHER";
    # version ����
    if(eregi("Gecko",$agent_env)) $agent[vr] = "6";
    else $agent[vr] = "4";
    # Mozilla ���� ���� ����
    $agent[co] = "mozilla";
  } else $agent[br] = "OTHER";

  return $agent;
}

# ���� ������ �������� UNIX_TIMESTAMP�� ���·� �ð��� �̾ƿ��� �Լ�
#
# date    - UNIX TIMESTAMP�� ���� �ð��� �°� ������ �������� ���
#           http://www.php.net/manual/function.date.php
# mktime  - ������ �ð��� UNIX TIMESTAMP�� ������
#           http://www.php.net/manual/function.mktime.php
#
function get_date() {
  $today = mktime(date("H")-12,0,0);
  return $today;
}

# �⺻���� �Խ����� ������ �������� �Լ�
function get_board_info($table) {
  global $o;

  # ���� ������ UNIX_TIMESTAMP�� ���ؿ�
  $today  = get_date();

  # date �ʵ带 ���ؼ� ���� �ö�� ���� ������ ������
  $sql    = search2sql($o);
  $result = sql_query("SELECT COUNT(1/(date > '$today')), COUNT(*) FROM $table $sql");
  $A = sql_fetch_array($result);

  $count[all]    = $A[1];	# ��ü �� ��
  $count[today]  = $A[0];	# ���� �� ��

  return $count;
}

# �Խ����� ��ü ������ ���� ���ϴ� �Լ�
function get_page_info($count, $page = 0) {
    global $board; # �Խ��� �⺻ ���� (config/global.ph)

    # ���� �� ���� ������ �� �� ���� ������ ��ü �������� ����
    # ���� ���� ���������� ��ȯ�ϸ� ��Ȯ�� ������ �������� ������ 1�� ����
    if($count[all] % $board[perno])
	$pages[all] = intval($count[all] / $board[perno]) + 1;
    else
	$pages[all] = intval($count[all] / $board[perno]);

    # $page ���� ������ �� ���� $pages[cur] ������ ������
    if($page)
	$pages[cur] = $page;

    # $pages[cur] ���� ������ 1�� ������
    if(!$pages[cur])
	$pages[cur] = 1;
    # $pages[cur] ���� ��ü ������ ������ Ŭ ��� ��ü ������ ���� ������
    if($pages[cur] > $pages[all])
	$pages[cur] = $pages[all];

    # $pages[no] ���� ������ $pages[cur] ���� �����Ͽ� ������. ��Ͽ���
    # �ҷ��� ���� ���� ��ȣ�� ����
    if(!$pages[no])
	$pages[no] = ($pages[cur] - 1) * $board[perno];

    # $pages[cur] ���� ���� ����(pre), ����(nex) ������ ���� ������
    if($pages[cur] > 1)
	$pages[pre] = $pages[cur] - 1;
    if($pages[cur] < $pages[all])
	$pages[nex] = $pages[cur] + 1;

    return $pages;
}

# ���� �� ����� ��� �������� �ִ� ������ �˾Ƴ��� ���� �Լ�
#
# intval - ������ ���������� ��ȯ��
#          http://www.php.net/manual/function.intval.php
function get_current_page($table, $idx) {
  global $board; # �Խ��� �⺻ ���� (config/global.ph)
  global $o;

  $sql = search2sql($o, 0);
  $count = get_board_info($table);

  # ������ ���� idx���� ū ��ȣ�� ���� ���� ������ ������
  $result     = sql_query("SELECT COUNT(*) FROM $table WHERE idx > '$idx' $sql");
  $count[cur] = sql_result($result, 0, "COUNT(*)");
  sql_free_result($result);

  # ������ ���� ������ �� �� ���� ������ �� ��° ���������� ������
  # (�������� 1���� �����ϱ� ������ 1�� ����)
  $page   = intval($count[cur] / $board[perno]) + 1;

  return $page;
}

# ������ ���� ����, �������� �������� �Լ�
function get_pos($table, $idx) {
    global $o;

    $sql    = search2sql($o, 0);
    
    $idxdp = $idx + 1;
    $idxdm = $idx - 1;
    $idxplus = $idx + 10;
    $idxminus = $idx - 10;

    # ������ ���� idx���� ���� ��ȣ�� ���� �� �߿� idx�� ���� ū �� (������)
    #$result    = sql_query("SELECT MAX(idx) AS idx FROM $table WHERE idx < '$idx' $sql");
    $result    = sql_query("SELECT MAX(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxminus' AND '$idxdm') $sql");
    $pos[next] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos[next]) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = '$pos[next]'");
	$next   = sql_fetch_array($result);
	sql_free_result($result);
        $next[title] = str_replace("&amp;","&",$next[title]);
	$next[title] = eregi_replace("(#|')","\\\\1",htmlspecialchars($next[title]));

	$pos[next] = $next[no];
	if($next[reto]) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = '$next[reto]'");
	    $next[num] = sql_result($result, 0, "num");
	    sql_free_result($result);
	    $pos[next_t] = "Reply of No.$next[num]: $next[title]";
	} else {
	    $pos[next_t] = "No.$next[num]: $next[title]";
	}
    }

    # ������ ���� idx���� ū ��ȣ�� ���� �� �߿� idx�� ���� ���� �� (������)
    #$result    = sql_query("SELECT MIN(idx) AS idx FROM $table WHERE idx > '$idx' $sql");
    $result    = sql_query("SELECT MIN(idx) AS idx FROM $table WHERE (idx BETWEEN '$idxdp' AND '$idxplus') $sql");
    $pos[prev] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos[prev]) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = '$pos[prev]'");
	$prev   = sql_fetch_array($result);
	sql_free_result($result);
        $prev[title] = str_replace("&amp;","&",$prev[title]);
	$prev[title] = eregi_replace("(#|')","\\\\1",htmlspecialchars($prev[title]));

	$pos[prev] = $prev[no];
	if($prev[reto]) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = '$prev[reto]'");
	    $prev[num] = sql_result($result, 0, "num");
	    sql_free_result($result);
	    $pos[prev_t] = "Reply of No.$prev[num]: $prev[title]";
	} else {
	    $pos[prev_t] = "No.$prev[num]: $prev[title]";
	}
    }

    return $pos;
}

# PHP�� microtime �Լ��� ������� ���� ���Ͽ� ��� �ð��� �������� �Լ�
#
# explode - ���� ���ڿ��� �������� ���ڿ��� ����
#           http://www.php.net/manual/function.explode.php
function get_microtime($old, $new) {
  $start = explode(" ", $old);
  $end = explode(" ", $new);

  return sprintf("%.2f", ($end[1] + $end[0]) - ($start[1] + $start[0]));
}
    
# �˸��� ������ �������� ���� ���� (html/head.ph)
#
# basename - ���� ��ο��� ���ϸ��� ������
#            http://www.php.net/manual/function.basename.php
function get_title() {
  global $board, $langs; # �Խ��� �⺻ ���� (config/global.ph)

  $title  = $board[title];

  # SCRIPT_NAME�̶�� ����ġ ȯ�� ������ ������ (���� PHP ����)
  $script = $_SERVER[SCRIPT_NAME];
  $script = basename($script);

  switch($script) {
    case "list.php":
      $title .= " $langs[get_v]";
      break;
    case "read.php":
      $title .= " $langs[get_r]";
      break;
    case "edit.php":
      $title .= " $langs[get_e]";
      break;
    case "write.php":
      $title .= " $langs[get_w]";
      break;
    case "reply.php":
      $title .= " $langs[get_re]";
      break;
    case "delete.php":
      $title .= " $langs[get_d]";
      break;
    case "user.php":
      $title .= " $langs[get_u]";
      break;
    case "regist.php":
      $title .= " $langs[get_rg]";
      break;
    case "security.php":
      $title .= " SECURITY WARNING";
      break;
  }

  return $title;
}

function get_article($table, $no, $field0 = "*", $field1 = "no") {
  global $langs;
  if(!$no)
    print_error("$langs[get_no]",250,150,1);

  $result  = sql_query("SELECT $field0 FROM $table WHERE $field1 = '$no'");
  $article = sql_fetch_array($result);
  sql_free_result($result);

  if(!$article)
    print_error("$langs[get_n]",250,150,1);

  return $article;
}

# ���� ũ�� ��� �Լ� by ��ĥ�� <san2@linuxchannel.net>
# $bfsize ������ bytes ������ ũ����
#
# number_formant() - 3�ڸ��� �������� �ĸ��� ���
function human_fsize($bfsize, $sub = "0") {
  $BYTES = number_format($bfsize) . " Bytes"; // 3�ڸ��� �������� �ĸ�

  if($bfsize < 1024) return $BYTES; # Bytes ����
  elseif($bfsize < 1048576) $bfsize = number_format(round($bfsize/1024)) . " KB"; # KBytes ����
  elseif($bfsize < 1073741827) $bfsize = number_format(round($bfsize/1048576)) . " MB"; # MB ����
  else $bfsize = number_format(round($bfsize/1073741827)) . " GB"; # GB ����

  if($sub) $bfsize .= "($BYTES)";

  return $bfsize;
} 

function viewfile($tail) {
  global $board, $table, $list, $upload;
  global $langs, $icons, $agent;

  $upload_file = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  $wupload_file = "./data/$table/$upload[dir]/$list[bcfile]/".urlencode($list[bofile]);

  $source1 = "<p><br>\n---- $list[bofile] $langs[inc_file] -------------------------- \n<p>\n<pre>\n";
  $source2 = "\n</pre>\n<br><br>";
  $source3 = "   <font color=red>$list[bofile]</font> file is broken link!!\n\n";

  if (@file_exists($upload_file)) {
    if (eregi("^(gif|jpg|png)$",$tail)) {
      $imginfo = GetImageSize($upload_file);
      if($agent[co] == "mozilla") $list[bofile] = urlencode($list[bofile]);
      $uplink_file = "./form.php?mode=photo&table=$table&f[c]=$list[bcfile]&f[n]=$list[bofile]&f[w]=$imginfo[0]&f[h]=$imginfo[1]";
      if($imginfo[0] > $board[width] - 6 && !eregi("%",$board[width])) {
        $p[vars] = $imginfo[0]/$board[width];
        $p[width] = $board[width] - 6;
        $p[height] = intval($imginfo[1]/$p[vars]);

        if(extension_loaded("gd")) {
          $ImgUrl = rawurlencode("$wupload_file");
          $ImgPath = "<IMG SRC=\"./image.php?path=$ImgUrl&width=$p[width]&height=$p[height]\" WIDTH=$p[width] HEIGHT=$p[height] BORDER=0>";
        } else
          $ImgPath = "<IMG SRC=\"$wupload_file\" WIDTH=$p[width] HEIGHT=$p[height] BORDER=0>";

        $p[up]  = "[ <B>Original Size</B> $imginfo[0] * $imginfo[1] ]<br>\n";
        $p[up] .= "<A HREF=javascript:new_windows(\"$uplink_file\",\"photo\",0,0,$imginfo[0],$imginfo[1])>$ImgPath</A>\n<P>\n";
      } else {
        $p[up] = "<IMG SRC=\"$wupload_file\" $imginfo[3] BORDER=0>\n<p>\n";
      }
    } else if (eregi("^(phps|txt|htm|shs)$",$tail)) {
      $view = file_operate($upload_file,"r",0,1200);
      $view = htmlspecialchars(cut_string($view,1000));
      if (filesize($upload_file) > 1000) $view = $view . " <p>\n ......$langs[preview]\n\n";

      $p[down] = "$source1$view$source2";
    } elseif (eregi("^(mid|wav|mp3)$",$tail)) {
      if($tail == "mp3" && $agent[co] == "mozilla")
        $p[up] = "[ MP3 file�� IE������ �����Ǽ� �ֽ��ϴ�. ]";
      elseif($agent[br] == "LYNX")
        $p[bo] = "";
      else
        $p[bo] = "<embed src=$upload_file autostart=true hidden=true mastersound>";
    } elseif (eregi("^(mpeg|mpg|asf|dat|avi|wmv)$",$tail)) {
      if($agent[br] == "MSIE") $p[up] = "<embed src=$upload_file autostart=true>";
    } elseif ($tail == "mov" && $agent[br] == "MSIE") {
      $p[up] = "<embed src=$upload_file autostart=true width=300 height=300 align=center>";
    } elseif ($tail == "swf") {
      $flash_size = $board[width] - 10;
      if($agent[br] == "MSIE" || $agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6)) $p[up] = "<embed src=$upload_file width=$flash_size height=$flash_size align=center>";
    }
  } else $p[down] = "$source1$source3$source2";

  return $p;
}

# ������ ������ �ް� ���� �Լ�
# p -> ���� ���
# m -> ���� �۵� ���(r-�б�,w-����,a-���ϳ����� ����)
# msg -> ���н� ���� �޼���
# s -> �����忡���� ������
# t -> �б��忡�� ������ ��ŭ ���� ������ �ƴϸ� �迭�� ����
#      ��ü�� ���� ������ ����
#
function file_operate($p,$m,$msg='',$s='',$t=0) {
  if($m == "r" || $m == "w" || $m == "a") {
    $m .= "b";
    
    # file point �� open
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

# http socket ���� ������ �Ͽ� html source �� �������� �Լ�
# ��� : HTTP/1.1 ���� ����
#
# $url -> �ش� ������ �ּ� (http:// �� ����)
# $size -> �ش� ������ size
# $file -> ��� ������ URI
# $type -> socket(1) ��� �Ǵ� fopen(null)
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

# ���� ��ϱ� üũ�� ���� �˰���
#
function get_spam_value($v) {
  $chk = explode(":",$v);
  $ran = preg_replace("/[^1-9]/i","",$_COOKIE[PHPSESSID]);
  $ran = ($ran > 99999) ? substr($ran,0,5) : $ran;
  $ret = $chk[0] * $ran - ($chk[1] * $chk[2]);

  return $ret;
}

# upload ���� �������� ����
#
function get_upload_value($up) {
  if($up[yesno]) {
    if($up[maxtime]) set_time_limit($up[maxtime]);
    # JSBoard ���� ������ �� �ִ� ���ε� �ִ� ������
    # �ִ밪�� POST ����Ÿ�� ���� post_max_size ���� 1M �� �۰� ��´�.
    $max = ini_get(post_max_size);
    if(preg_match("/M$/i",$max)) {
      $max = (preg_replace("/M$/i","",$max) - 1) * 1024 * 1024;
    } elseif (preg_match("/K$/i",$max)) {
      $max = (preg_replace("/K$/i","",$max) - 1) * 1024;
    } else {
      $max -= 1024;
    }
    ini_set(upload_max_filesize,$max);
    $size = ($up[maxsize] > $max) ? $max : $up[maxsize];

    return $size;
  } else return 0;
}
?>
