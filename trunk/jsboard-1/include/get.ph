<?
# �� ���� �������� IP �ּ� Ȥ�� �����θ��� �������� �Լ�
# license : OOPS_License (http://www.oops.org/OOPS_License)
# HTTP_X_FORWARDED_FOR - proxy server�� �����ϴ� ȯ�� ����
# getenv        - ȯ�� �������� ������
#                 http://www.php.net/manual/function.getenv.php
# gethostbyaddr - IP �ּҿ� ��ġ�ϴ� ȣ��Ʈ���� ������
#                 http://www.php.net/manual/function.gethostbyaddr.php
function get_hostname($reverse = 0,$e = 0) {
  # proxy �� ���ؼ� ���ö� �� ip address ����
  $host = getenv("HTTP_X_FORWARDED_FOR");

  # proxy�� ������ �ʰ� ���� �Ҷ� ����ġ ȯ�� ������
  # REMOTE_ADDR���� �������� IP�� ������
  $host  = $host ? $host : getenv("REMOTE_ADDR");
  $check = $reverse ? @gethostbyaddr($host) : "";
  $host = $check ? $check : $host;

  return $host;
}

# ������ ����� ����ϴ� �������� �˱� ���� ���Ǵ� �Լ�, ����� FORM
# �Է�â�� ũ�Ⱑ ���������� Ʋ���� �����Ǵ� ���� �����ϱ� ���� ����
#
# getenv - ȯ�� �������� ������
#          http://www.php.net/manual/function.getenv.php
function get_agent() {
  $agent_env = getenv("HTTP_USER_AGENT");

  # $agent �迭 ���� [br] ������ ����
  #                  [os] �ü��
  #                  [ln] ��� (�ݽ�������)
  if(ereg("MSIE", $agent_env)) {
    $agent[br] = "MSIE";
    if(ereg("NT", $agent_env)) {
      $agent[os] = "NT";
    } else if(ereg("Win", $agent_env)) {
      $agent[os] = "WIN";
    } else $agent[os] = "OTHER";
  } else if(ereg("^Mozilla", $agent_env)) {
    $agent[br] = "MOZL";
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
  } else if(ereg("Lynx", $agent_env)) {
    $agent[br] = "LYNX";
  } else $agent[br] = "OTHER";

  return $agent;
}

# ���� ������ �������� UNIX_TIMESTAMP�� ���·� �ð��� �̾ƿ��� �Լ�
#
# time    - ���� �ð��� UNIX TIMESTAMP�� ������
#           http://www.php.net/manual/function.time.php
# date    - UNIX TIMESTAMP�� ���� �ð��� �°� ������ �������� ���
#           http://www.php.net/manual/function.date.php
# mktime  - ������ �ð��� UNIX TIMESTAMP�� ������
#           http://www.php.net/manual/function.mktime.php
# explode - ���� ���ڿ��� �������� ���ڿ��� ����
#           http://www.php.net/manual/function.explode.php
function get_date() {
  # ������ �ð��� $time�� ����
  $time  = time();
  # ��, ��, ���� ������ ������ ����
  $date  = date("m:d:Y", $time);
  $date = explode(":", $date);

  # ���� ��¥�� ������ �������� UNIX_TIMESTAMP �������� ����
  #$today = mktime(0, 0, 0, $date[0], $date[1], $date[2]);
  $today = mktime(date("H")-12, 0, 0, date("m"), date("d"), date("Y"));

  return $today;
}

# �⺻���� �Խ����� ������ �������� �Լ�
function get_board_info($table) {
  global $o;

  # ���� ������ UNIX_TIMESTAMP�� ���ؿ�
  $today  = get_date();

  # date �ʵ带 ���ؼ� ���� �ö�� ���� ������ ������
  $sql    = search2sql($o, 0);

  if(!$sql) {
    $result = sql_query("SELECT COUNT(*) FROM $table WHERE date > $today");
    $tcount = sql_result($result, 0, "COUNT(*)");
    sql_free_result($result);
  }

  # �Խ����� ��ü ���� ������ ������
  $sql    = search2sql($o);
  $result = sql_query("SELECT COUNT(*) FROM $table $sql");
  $acount = sql_result($result, 0, "COUNT(*)");
  sql_free_result($result);

  $count[all]    = $acount;	// ��ü �� ��
  $count[today]  = $tcount;	// ���� �� ��
    
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
  $result     = sql_query("SELECT COUNT(*) FROM $table WHERE idx > $idx $sql");
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
    
    # ������ ���� idx���� ���� ��ȣ�� ���� �� �߿� idx�� ���� ū �� (������)
    $result    = sql_query("SELECT MAX(idx) AS idx FROM $table WHERE idx < $idx $sql");
    $pos[next] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos[next]) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = $pos[next]");
	$next   = sql_fetch_array($result);
	sql_free_result($result);
        $next[title] = str_replace("&amp;","&",$next[title]);

	$pos[next] = $next[no];
	if($next[reto]) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = $next[reto]");
	    $next[num] = sql_result($result, 0, "num");
	    sql_free_result($result);
	    $pos[next_t] = "Reply of No.$next[num]: $next[title]";
	} else {
	    $pos[next_t] = "No.$next[num]: $next[title]";
	}
    }

    # ������ ���� idx���� ū ��ȣ�� ���� �� �߿� idx�� ���� ���� �� (������)
    $result    = sql_query("SELECT MIN(idx) AS idx FROM $table WHERE idx > $idx $sql");
    $pos[prev] = sql_result($result, 0, "idx");
    sql_free_result($result);
    if($pos[prev]) { 
	$result = sql_query("SELECT no, title, num, reto FROM $table WHERE idx = $pos[prev]");
	$prev   = sql_fetch_array($result);
	sql_free_result($result);
        $prev[title] = str_replace("&amp;","&",$prev[title]);

	$pos[prev] = $prev[no];
	if($prev[reto]) {
	    $result    = sql_query("SELECT num FROM $table WHERE no = $prev[reto]");
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
  # �־��� ���ڿ��� ���� (sec, msec���� ��������)
  $old = explode(" ", $old);
  $new = explode(" ", $new);

  $time[msec] = $new[0] - $old[0];
  $time[sec]  = $new[1] - $old[1];

  if($time[msec] < 0) {
    $time[msec] = 1.0 + $time[msec];
    $time[sec]--;
  }

  $time = sprintf("%.2f", $time[sec] + $time[msec]);

  return $time;
}
    
# �˸��� ������ �������� ���� ���� (html/head.ph)
#
# getenv   - ȯ�� �������� ������
#            http://www.php.net/manual/function.getenv.php
# basename - ���� ��ο��� ���ϸ��� ������
#            http://www.php.net/manual/function.basename.php
function get_title() {
  global $board, $langs; # �Խ��� �⺻ ���� (config/global.ph)

  $title  = $board[title];

  # SCRIPT_NAME�̶�� ����ġ ȯ�� ������ ������ (���� PHP ����)
  $script = getenv("SCRIPT_NAME");
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
  }

  return $title;
}

function get_article($table, $no, $field0 = "*", $field1 = "no") {
  global $langs;
  if(!$no)
    print_error("$langs[get_no]");

  $result  = sql_query("SELECT $field0 FROM $table WHERE $field1 = $no");
  $article = sql_fetch_array($result);
  sql_free_result($result);

  if(!$article)
    print_error("$langs[get_n]");

  return $article;
}

function get_theme_img($t) {
  if (@file_exists("./data/$t/default.themes"))
    $theme = readlink("./data/$t/default.themes");
  else  
    $theme = readlink("./config/default.themes");
  $theme = eregi_replace("(themes|config|\/|\.)","",$theme);

  if (is_dir("images/$theme"))
    $path = "images/$theme";
  else $path = "images";

  return $path;
}

# ���� ũ�� ��� �Լ� by ��ĥ�� <san2@linuxchannel.net>
# $bfsize ������ bytes ������ ũ����
#
# number_formant() - 3�ڸ��� �������� �ĸ��� ���
function human_fsize($bfsize, $sub = "0") {
  $bfsize_Bytes = number_format($bfsize);

  if ($bfsize >= 1024 && $bfsize < 1048576) { // KBytes ���� 
    $bfsize_KB = round($bfsize/1024); 
    $bfsize_KB = number_format($bfsize_KB); 

    if ($sub) $bfsize = "$bfsize_KB KB($bfsize_Bytes Bytes)"; 
    else $bfsize = "$bfsize_KB Kb";

  } elseif ($bfsize >= 1048576) { // MB ���� 
    $bfsize_MB = round($bfsize/1048576); 
    $bfsize_MB = number_format($bfsize_MB); 

    if ($sub) $bfsize = "$bfsize_MB MB($bfsize_Bytes Bytes)";
    else $bfsize = "$bfsize_MB Mb";

  } else
    $bfsize = "$bfsize_Bytes Bytes"; 

  return $bfsize; 
} 

function viewfile($tail) {
  global $board, $table, $list, $upload;
  global $langs, $icons;

  $agent = get_agent();
  $upload_file = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";

  $source1 = "<p><br>\n---- $list[bofile] $langs[inc_file] -------------------------- \n<p>\n<pre>\n";
  $source2 = "\n</pre>\n<br><br>";
  $source3 = "   <font color=red>$list[bofile]</font> file is broken link!!\n\n";

  if (@file_exists($upload_file)) {
    if (eregi("^(gif|jpg|png)$",$tail)) {
      $imginfo = GetImageSize($upload_file);
      $uplink_file = "./form.php?mode=photo&table=$table&f[c]=$list[bcfile]&f[n]=$list[bofile]&f[w]=$imginfo[0]&f[h]=$imginfo[1]";
      if($imginfo[0] > $board[width] - 6 && !eregi("%",$board[width])) {
        $p[vars] = $imginfo[0]/$board[width];
        if($board[img] != "yes") $p[width] = $board[width] - 6;
        else $p[width] = $board[width] - $icons[size] * 2 - 6;
        $p[height] = intval($imginfo[1]/$p[vars]);
        $p[up]  = "[ <b>Orizinal Size</b> $imginfo[0] * $imginfo[1] ]<br>\n";
        $p[up] .= "<a href=javascript:new_windows(\"$uplink_file\",\"photo\",0,0,$imginfo[0],$imginfo[1])><img src=\"$upload_file\" width=$p[width] height=$p[height] border=0></a>\n<p>\n";
      } else {
        $p[up] = "<img src=\"$upload_file\" $imginfo[2]>\n<p>\n";
      }
    } else if (eregi("^(phps|txt|htmls|htm|shs)$",$tail)) {
      $fsize = filesize($upload_file);
      $fsize_ex = 1000;
      if ($fsize == $fsize_ex) $check = 1;
      if ($tail == "txt" && $fsize > $fsize_ex) $fsize = $fsize_ex;

      $fp = fopen($upload_file, "r");
      $view = fread($fp,$fsize);
      fclose($fp);
      $view = htmlspecialchars($view);
      if ($fsize == $fsize_ex && !$check) $view = $view . " <p>\n ......$langs[preview]\n\n";

      $p[down] = "$source1$view$source2";
    } elseif (eregi("^(mid|wav|mp3)$",$tail)) {
      if($tail == "mp3" && $agent[br] == "MOZL")
        $p[up] = "[ MP3 file�� IE������ �����Ǽ� �ֽ��ϴ�. ]";
      elseif($agent[br] == "LYNX")
        $p[bo] = "";
      else
        $p[bo] = "<embed src=$upload_file autostart=true hidden=true mastersound>";
    } elseif (eregi("^(mpeg|mpg|asf|dat|avi)$",$tail)) {
      if($agent[br] == "MSIE") $p[up] = "<embed src=$upload_file autostart=true>";
    } elseif ($tail == "mov" && $agent[br] == "MSIE") {
      $p[up] = "<embed src=$upload_file autostart=true width=300 height=300 align=center>";
    } elseif ($tail == "swf") {
      $flash_size = $board[width] - 10;
      if($agent[br] == "MSIE") $p[up] = "<embed src=$upload_file width=$flash_size height=$flash_size align=center>";
    }
  } else $p[down] = "$source1$source3$source2";

  return $p;
}

?>
