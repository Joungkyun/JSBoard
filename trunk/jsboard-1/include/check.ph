<?
# table �̸��� meta character �� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
# $name -> ������
# $i    -> null �̶� ������� ��� 1
# $t    -> table �̸� �˻�� 1
#
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error(" $name Value Name Missing! You must specify a value");
  if ($t && !preg_match("/^[a-z]/i",$name)) print_error("$name Value must start with an alphabet");
  if (preg_match("/[^a-z0-9_-]/i",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters");
  if ($t && preg_match("/^as$/i",$name)) print_error("Cat't use table name as &quot;as&quot;");
}

# ���ڿ��� �ѱ��� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
#
# ord    - ������ ASCII ���� ������
#          http://www.php.net/manual/function.ord.php
function is_hangul($char) {
  # Ư�� ���ڰ� �ѱ��� ������(0xA1A1 - 0xFEFE)�� �ִ��� �˻�
  $char = ord($char);

  if($char >= 0xa1 && $char <= 0xfe)
    return 1;
}

# ���ĺ����� �׸��� �빮��(0x41 - 0x5a)���� �ҹ���(0x61 - 0x7a)����
# �˻��ϴ� �Լ�
#
# ord - ������ ASCII ���� ������
#       http://www.php.net/manual/function.ord.php
function is_alpha($char) {
  $char = ord($char);

  if($char >= 0x61 && $char <= 0x7a)
    return 1;
  if($char >= 0x41 && $char <= 0x5a)
    return 2;
}

# URL�� ��Ȯ�� ������ �˻��ϴ� �Լ�
#
# eregi         - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
#                 http://www.php.net/manual/function.eregi.php
# eregi_replace - ���� ǥ������ �̿��� ġȯ (��ҹ��� ����)
#                 http://www.php.net/manual/function.eregi-replace.php
function check_url($url) {
  $url = trim($url);

  # ��������(http://, ftp://...)�� ��Ÿ���� �κ��� ���� �� �⺻������
  # http://�� ����
  if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url))
    $url = eregi_replace("^", "http://", $url);

  if(!eregi("(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[][\xA1-\xFEa-zA-Z0-9,:&#@=_~%?\/.+-]+$", $url))
    return;

  return $url;
}

# E-MAIL �ּҰ� ��Ȯ�� ������ �˻��ϴ� �Լ�
#
# eregi - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
#         http://www.php.net/manual/function.eregi.php
function check_email($url) {
  if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$",trim($url))) return $url;
  else return;
}

# �н����� �� �Լ�
#
# crpyt - ���ڿ��� DES�� ��ȣȭ��
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table, $no, $passwd) {
  global $sadmin, $admin;

  if ($table && $no) {
    $result = sql_query("SELECT passwd FROM $table WHERE no = '$no'");
    $crypt  = sql_result($result, 0, "passwd");
    sql_free_result($result);
  }

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(crypt($passwd, $crypt) == $crypt || $sadmin[passwd] == $spasswd || $admin[passwd] == $upasswd) {
    return 1;
  }
}

# ���� �˻� �Լ�
function check_spam($str, $spam_list = "config/spam_list.txt") {
  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # $list �迭�� ���� ��ŭ for���� ���� $spam_list ���Ͽ� �����Ǿ� �ִ�
  # ���ڿ���� ��ġ�ϴ� ���ڿ��� $spam_str�� �ִ��� �˻���, ���� ���
  # �������� �Ǵ��ϰ� ������ 1�� ��ȯ��, ���� ���� 0�� ��ȯ��
  for($co = 0; $co < count($list); $co++) {
    $list[$co] = trim($list[$co]);
    if($list[$co] && eregi($list[$co], $str)) {
      return 1;
    }
  }
  return 0;
}

# ��� ������ ������ üũ
# file() - file�� �о� ���پ� �迭�� ����
#
function chk_spam_browser($file = "config/allow_browser.txt") {
  global $HTTP_SERVER_VARS;
  $agent_env = $HTTP_SERVER_VARS["HTTP_USER_AGENT"];

  if(@file_exists($file)) {
    $br = file($file);
    for($i=0;$i<sizeof($br);$i++) {
      $br[$i] = trim($br[$i]);
      if (eregi($br[$i],$agent_env)) {
        return 1;
        break;
      }
    }
    return 0;
  } else return 1;
}

function check_net($ipaddr, $network, $netmask) {
  $net[ip] = explode(".", $ipaddr);
  $net[nw] = explode(".", $network);
  $net[nm] = explode(".", $netmask);

  for($co = 0; $co < count($net[ip]); $co++) {
    $nw[ip] = sprintf("%x", $net[ip][$co]);
    $nw[nw] = sprintf("%x", $net[nw][$co]);
    $nw[nm] = sprintf("%x", $net[nm][$co]);

    $mask1 .= $nw[nw] & $nw[nm];
    $mask2 .= $nw[ip] & $nw[nm];
  }
  if($mask1 == $mask2)
    return 1;
}

# ������� üũ �Լ�
#
function enable_write($super,$user,$check,$ena, $cena = 1) {
  global $kind, $table, $no, $wcheck, $langs, $page, $adminsession;

  if($no) $nom = "&no=$no";
  $page = $page? "&page=$page" : "";

  if ($wcheck && !$adminsession && !$check) {
    SetCookie("pcheck","",0);
    header("Location: auth_ext.php?table=$table&ena=$ena&cena=$cena&kind=$kind$page$nom");
    die;
  }

  if($adminsession) {
    $pscheck = $adminsession;
  } else {
    $pucheck = crypt($check,$user);
    $pscheck = crypt($check,$super);
  }

  $selffile ="act.php?o[at]=se&o[se]=logout&table=$table&kind=$kind$nom";
  if (!$ena) $user_m = "$langs[chk_a]";
  $ment = "$langs[chk_wa]";

  if (!$ena) {
    if($super != $pscheck) missmatch_passwd($selffile,$ment);
  } else if($ena && !$cena) {
    if($super != $pscheck && $user != $pucheck) missmatch_passwd($selffile,$ment);
  }
}

# upload file �̸����⸦ ���� file type �˻�
# substr - ���ڿ��� �Ϻκ��� ��ȯ�Ѵ�.
# strchr - ���ڿ��� ���������� ��Ÿ���� ��ġ�� ���Ѵ�
#
function check_filetype($filetype) {
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}

function icon_check($t,$fn) {
  if (preg_match("/^(hwp|mov|txt)$/i",$t)) $icon = "$t.gif";
  else if (preg_match("/^(exe|com)$/i",$t)) $icon = "exe.gif";
  else if (preg_match("/^(zip|arj|gz|lha|rar|tar|tgz|ace)$/i",$t)) $icon = "comp.gif";
  else if (preg_match("/^(php|php3|phps|vbs)$/i",$t)) {
    if (preg_match("/(_htm|_cgi|_pl|_shtm|_sh)/i",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if (preg_match("/^(avi|mpg|mpeg|asf|swf)$/i",$t)) $icon = "mpeg.gif";
  else if (preg_match("/^(jpg|gif|bmp|psd|png)$/i",$t)) $icon = "pic.gif";
  else if (preg_match("/^(ppt|xls|doc)$/i",$t)) $icon = "doc.gif";
  else if (preg_match("/^(ra|ram|rm)$/i",$t)) $icon = "ra.gif";
  else if (preg_match("/^(mp3|mp2|wav|mid)$/i",$t)) $icon = "mp3.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (preg_match("/(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch|\.vbs|\.ppt|\.xls)$/i",$list[bofile])) {
    $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
  } else {
    if ($list[bfsize] < 51200) $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
    else $dn = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  }
  return $dn;
}

# upload file �̸��� Ư�� ���ڰ� ��� �� �ִ��� �˻�
#
function upload_name_chk($f) {
  global $langs;

  if(!trim($f)) print_error($langs[act_de]);

  # file �̸����� Ư�����ڰ� ������ ���� ���
  # ����/�ѱ��� �����
  if ( preg_replace ("/[\w\d._\-]|[\xB0-\xC8\xCA-\xFD][\xA1-\xFE]/",'', urldecode ($f)) ) {
    print_error($langs[act_de]);
    exit;
  }

  # hidden file �̳� multiple dot ������� ����
  if ( preg_match ("/^\.|\.\.+/", urldecode ($f)) ) {
    print_error($langs[act_de]);
    exit;
  }
}

# ���������� ������ ���� ���� üũ �Լ�.
# referer ���� rmail[bbs] ������ ������ ���� ������ ��쿡��
# �����
#
function check_location($n=0) {
  global $board, $langs, $HTTP_SERVER_VARS;

  if($n) {
    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,$HTTP_SERVER_VARS["HTTP_REFERER"]));
    $l = gethostbyname(preg_replace($sre,$tre,$rmail[bbshome]));

    if ($r != $l) {
      print_error("$langs[chk_lo]");
      return 0;
    } else return 1;
  } else return 1;
}

# TABLE �� ��Ȯ�ϰ� ����ߴ��� üũ�ϴ� �Լ�
# str  -> üũ�� ���ڿ�
# rep  -> 1 �� ��� �����޽��� ��� ����� echo ��
#
function check_htmltable($str,$rep='') {
  global $langs;

  # table tag �鸸 ���ΰ� ��� ����
  $s[] = "/>[^<]*</i";
  $s[] = "/#|@/i";
  $s[] = "/<(\/?(TABLE|TR|TD|TH|IFRAME))[^>]*>/i";
  $s[] = "/^[^#]*/i";
  $s[] = "/(TABLEEMARK@)[^#]*(#TABLESMARK)/i";
  $s[] = "/<[^>]*>/i";
  $s[] = "/#TABLESMARK#/i";
  $s[] = "/@TABLEEMARK@/i";
  $s[] = "/(\r?\n)+/i";

  $d[] = ">\n<";
  $d[] = "";
  $d[] = "\n#TABLESMARK#\\1@TABLEEMARK@\n";
  $d[] = "";
  $d[] = "\\1\\2";
  $d[] = "";
  $d[] = "\n<";
  $d[] = ">\n";
  $d[] = "\n";

  $str = trim(preg_replace($s,$d,$str));

  # table tag �鸸 ���� ���� \n �� �������� �迭�� ����
  $ary = explode("\n",$str);

  for($i=0;$i<sizeof($ary);$i++) {
    if(strtoupper($ary[$i]) == "<TABLE>") $opentable++;
    elseif(strtoupper($ary[$i]) == "</TABLE>") $clstable++;
    elseif(strtoupper($ary[$i]) == "<TH>") $openth++;
    elseif(strtoupper($ary[$i]) == "</TH>") $clsth++;
    elseif(strtoupper($ary[$i]) == "<TR>") $opentr++;
    elseif(strtoupper($ary[$i]) == "</TR>") $clstr++;
    elseif(strtoupper($ary[$i]) == "<TD>") $opentd++;
    elseif(strtoupper($ary[$i]) == "</TD>") $clstd++;
    elseif(strtoupper($ary[$i]) == "<IFRAME>") $openif++;
    elseif(strtoupper($ary[$i]) == "</IFRAME>") $clsif++;
  }

  if(!$rep) {
    if($opentable != $clstable) $msg = $langs[chk_tb]."\n";
    if($openth != $clsth) $msg .= $langs[chk_th]."\n";
    if($opentr != $clstr) $msg .= $langs[chk_tr]."\n";
    if($opentd != $clstd) $msg .= $langs[chk_td]."\n";
    if($openif != $clsif) $msg .= $langs[chk_if]."\n";

    $msg = $msg ? $langs[chk_ta]."\n\nError Check:\n".trim($msg)."\n" : "";

    if($msg) print_error($msg);
  } else {
    echo "\n##  TABLE CHECK RESULT\n".
         "##  ----------------------------------------------------------------------\n".
         "##  OPEN  TABLE  TAG : $opentable\n".
         "##  CLOSE TABLE  TAG : $clstable\n##  \n".

         "##  OPEN  TH     TAG : $openth\n".
         "##  CLOSE TH     TAG : $clsth\n##  \n".

         "##  OPEN  TR     TAG : $opentr\n".
         "##  CLOSE TR     TAG : $clstr\n##  \n".

         "##  OPEN  TD     TAG : $opentd\n".
         "##  CLOSE TD     TAG : $clstd\n\n".

         "##  OPEN  IFRAME TAG : $openif\n".
         "##  CLOSE IFRAME TAG : $clsif\n\n";
  }
}

# PHP �� ������ üũ�ϴ� �Լ�.
# php ������ 4.1 ���� ���� ��� ���
# version => ���� ����
# v       => 1 (���� ������ version ���� ���� ��� ��)
#            0 (���� ������ version ���� ���� ��� ��)
#
function check_phpver($version,$v=0) {
  $phpversion = phpversion();
  if(preg_match("/pl[0-9]/i",$version))
    $ver_pl = preg_replace("/[0-9.]+pl([0-9]+).*/i","\\1",$version);
  $ver = explode(".",preg_replace("/([0-9.]+)[^0-9\.].*/i","\\1",$version));

  if(preg_match("/pl[0-9]/i",$phpversion))
    $chk_pl = preg_replace("/[0-9.]+pl([0-9]+).*/i","\\1",$phpversion);
  $chk = explode(".",preg_replace("/([0-9.]+)[^0-9.].*/i","\\1",$phpversion));

  if($v) {
    # ���� ������ ���� �������� ���� ��� ��
    if($ver[0] > $chk[0]) return 1;
    elseif ($ver[0] == $chk[0]) {
      if($ver[1] > $chk[1]) return 1;
      elseif ($ver[1] == $chk[1]) {
        if($ver[2] > $chk[2]) return 1;
        elseif ($ver[2] == $chk[2]) {
          if($ver_pl > $chk_pl) return 1;
          else return 0;
        } else return 0;
      } else return 0;
    } else return 0;
  } else {
    # ���� ������ ���� �������� ���� ��� ��
    if($ver[0] < $chk[0]) return 1;
    elseif ($ver[0] == $chk[0]) {
      if($ver[1] < $chk[1]) return 1;
      elseif ($ver[1] == $chk[1]) {
        if($ver[2] < $chk[2]) return 1;
        elseif ($ver[2] == $chk[2]) {
          if($ver_pl < $chk_pl) return 1;
          else return 0;
        } else return 0;
      } else return 0;
    } else return 0;
  }
}

function check_access($c=0,$wips='',$ips='') {
  global $HTTP_SERVER_VARS, $langs;

  if($c) {
    # global.ph �� $board[ipbl] �� �����ϸ� ��ħ
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # ���� �������� �������� �ʰų� ips ������ ���ų�, �������� �ڽ��̶�� üũ ����
    if(!trim($ips) || !$HTTP_SERVER_VARS[REMOTE_ADDR] ||
       $HTTP_SERVER_VARS[REMOTE_ADDR] == $HTTP_SERVER_VARS[SERVE_ADDR]) return;

    # spoofing üũ
    $ipchk = explode(".",$HTTP_SERVER_VARS[REMOTE_ADDR]);
    for($j=1;$j<4;$j++) $ipchk[$j] = $ipchk[$j] ? $ipchk[$j] : 0;
    # �� �ڸ��� ���� 255 ���� ũ�� ip �ּҸ� ����Ƿ� üũ 
    if($ipchk[0] > 255 || $ipchk[1] > 255 || $ipchk[2] > 255 || $ipchk[3] > 255)
      print_error($langs[chk_sp]);

    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$HTTP_SERVER_VARS[REMOTE_ADDR])) $val = 1;
    }

    if($val) print_error($langs[chk_bl]);
  }
}

# hyper link �� ���� ������ ����
# m -> 0 : ips �� ��ϵ� Ip ������ ��ũ�� ���
#      1 : ips �� ��ϵ� Ip ������ ��ũ�� ����
#
function check_dhyper($c=0,$am=0,$wips='',$m=0,$ips='') {
  global $langs;

  # $c ������ �ְ�, list read from write page ������ üũ
  if($c && preg_match("/(list|read|form|write)\.php/i",$_SERVER[PHP_SELF])) {
    # global.ph �� $board[dhyper] �� ���� �Ǿ� ������ üũ ����� ��ħ
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # $i = 0 -> ��ü ���ο����� ���� üũ
    # $i = 1 -> �Խ��� ���ο����� ���� üũ
    for($i=0;$i<2;$i++) {
      if($i === 0 && !trim($wips)) continue;
      $ips_value = ($i === 0) ? $wips : $ips;
      $m_value = ($i === 0) ? $am : $m;

      # ���۷��� �������� �ʰų� ips_value ������ ������ üũ ����
      if(!trim($ips_value) || !$_SERVER[HTTP_REFERER]) return;

      # ���۷����� ���� �̸��� ����
      preg_match("/^(http:\/\/)?([^\/]+)/i",$_SERVER[HTTP_REFERER],$chks);
      # ������ �̸��� ip �� ����
      $chk = gethostbyname($chks[2]);

      # chk �� �ڽŰ� �����ϸ� üũ ����
      if($chk == $_SERVER[SERVER_ADDR]) return;

      $addr = explode(";",$ips_value);
      for($j=0;$j<sizeof($addr);$j++) {
        if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$j])) $addr[$j] .= ".";
        $addr[$j] = str_replace(".","\.",$addr[$j]);
        if(preg_match("/^$addr[$j]/i",$chk)) $val = 1;
      }
      switch($m_value) {
        case '1' :
          if($val) print_error($langs[chk_hy]);
          break;
        default:
          if(!$val) print_error($langs[chk_hy]);
          break;
      }
    }
  }
}

# spam ��ϱ� üũ �Լ�
function check_spamer($anti,$wkey) {
  global $langs,$o;

  if($o[at] == "p" || $o[at] == "r") {
    if(!$anti || !preg_match("/^[0-9]+:[0-9]+:[0-9]+$/i",$anti)) print_error($langs[chk_an]);
    if($wkey != get_spam_value($anti)) print_error($langs[chk_sp]);
  }
}
?>
