<?
// ���ڿ��� �ѱ��� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
//
// ord    - ������ ASCII ���� ������
//          http://www.php.net/manual/function.ord.php3
function is_hangul($char) {
  // Ư�� ���ڰ� �ѱ��� ������(0xA1A1 - 0xFEFE)�� �ִ��� �˻�
  $char = ord($char);

  if($char >= 0xa1 && $char <= 0xfe)
    return 1;
}

// ���ĺ����� �׸��� �빮��(0x41 - 0x5a)���� �ҹ���(0x61 - 0x7a)����
// �˻��ϴ� �Լ�
//
// ord - ������ ASCII ���� ������
//       http://www.php.net/manual/function.ord.php3
function is_alpha($char) {
  $char = ord($char);

  if($char >= 0x61 && $char <= 0x7a)
    return 1;
  if($char >= 0x41 && $char <= 0x5a)
    return 2;
}

// URL�� ��Ȯ�� ������ �˻��ϴ� �Լ�
//
// eregi         - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
//                 http://www.php.net/manual/function.eregi.php3
// eregi_replace - ���� ǥ������ �̿��� ġȯ (��ҹ��� ����)
//                 http://www.php.net/manual/function.eregi-replace.php3
function check_url($url) {
  $url = trim($url);

  // ��������(http://, ftp://...)�� ��Ÿ���� �κ��� ���� �� �⺻������
  // http://�� ����
  if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url))
    $url = eregi_replace("^", "http://", $url);

  if(!eregi("(http|https|ftp|telnet|news):\/\/[a-z0-9-]+\.[][a-zA-Z0-9:&#@=_~%\-\?\/\.\+]+", $url))
    return;
    
  return $url;
}

// E-MAIL �ּҰ� ��Ȯ�� ������ �˻��ϴ� �Լ�
//
// eregi - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
//         http://www.php.net/manual/function.eregi.php3
function check_email($email) {
  $url = trim($email);

  if(!eregi("^[a-z0-9_-]+@[a-z0-9-]+\.[a-z0-9-]+", $email))
    return;
    
  return $email;
}

// �н����� �� �Լ�
//
// crpyt - ���ڿ��� DES�� ��ȣȭ��
//         http://www.php.net/manual/function.crypt.php3
function check_passwd($table, $no, $passwd) {
  global $sadmin, $admin;

  if ($table && $no) {
    $result = sql_query("SELECT passwd FROM $table WHERE no = $no");
    $crypt  = sql_result($result, 0, "passwd");
    sql_free_result($result);
  }

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(crypt($passwd, $crypt) == $crypt || $sadmin[passwd] == $spasswd || $admin[passwd] == $upasswd) {
    return 1;
  }
}

// ���� �˻� �Լ�
function check_spam($str, $spam_list = "config/spam_list.txt") {
  // $spam_list�� ������ ������ �б� ����(r)���� ����
  $fp = fopen($spam_list, "r");
  // $spam_list�� ������ ������ ũ�⸸ŭ ������ �о� $fr�� ����
  $fr = fread($fp, filesize($spam_list));
  fclose($fp);

  // $fr�� ���� \n ���ڷ� �����Ͽ� $list �迭�� ����
  $list = explode("\n", $fr);

  // $list �迭�� ���� ��ŭ for���� ���� $spam_list ���Ͽ� �����Ǿ� �ִ�
  // ���ڿ���� ��ġ�ϴ� ���ڿ��� $spam_str�� �ִ��� �˻���, ���� ���
  // �������� �Ǵ��ϰ� ������ 1�� ��ȯ��, ���� ���� 0�� ��ȯ��
  for($co = 0; $co < count($list); $co++) {
    $list[$co] = eregi_replace("(\r|\n)","",$list[$co]);
    if($list[$co] && eregi($list[$co], $str)) {
      return 1;
    }
  }

  return 0;
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

// ������� üũ �Լ�
//
function enable_write($super,$user,$check,$ena, $cena = 1) {
  global $kind, $table, $no, $wcheck, $langs;

  if($no) $nom = "&no=$no";
  if ($wcheck) {
    header("Location: auth_ext.php3?table=$table&ena=$ena&cena=$cena&kind=$kind$nom");
    die;
  }

  $pucheck = crypt($check,$user);
  $pscheck = crypt($check,$super);

  $selffile ="auth_ext.php3?table=$table&kind=$kind$nom";
  if (!$ena) $user_m = "$langs[chk_a]";
  $ment = "$langs[chk_wa]";

  if (!$ena) {
    if($super != $pscheck) missmatch_passwd($selffile,$ment);
  } else if($ena && !$cena) {
    if($super != $pscheck && $user != $pucheck) missmatch_passwd($selffile,$ment);
  }
}

// upload file �̸����⸦ ���� file type �˻�
// substr - ���ڿ��� �Ϻκ��� ��ȯ�Ѵ�.
// strchr - ���ڿ��� ���������� ��Ÿ���� ��ġ�� ���Ѵ�
//
function check_filetype($filetype) {
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}

function icon_check($tail,$fn) {
  if ($tail == "rm" || $tail == "doc" || $tail == "exe" || $tail == "hwp" || $tail == "mp3" || $tail == "mov" || $tail == "txt") $icon = "$tail.gif";
  else if ($tail == "zip" || $tail == "arj" || $tail == "gz" || $tail == "lha" || $tail == "rar" || $tail == "tar" || $tail == "tgz") $icon = "comp.gif";
  else if ($tail == "php" || $tail == "php3" || $tail == "phps") {
    if (eregi("(_htm|_cgi|_pl|_shtm|_sh)",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if ($tail == "avi" || $tail == "mpg" || $tail == "mpeg") $icon = "mpeg.gif";
  else if ($tail == "jpg" || $tail == "gif" || $tail == "bmp" || $tail == "psd") $icon = "pic.gif";
  else if ($tail == "ppt" || $tail == "xls") $icon = "doc.gif";
  else if ($tail == "ra" || $tail == "ram") $icon = "ra.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (eregi("(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch)$",$list[bofile])) {
    $dn = "act.php3?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
  } else {
    if ($list[bfsize] < 51200) $dn = "act.php3?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
    else $dn = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  }
  return $dn;
}

?>
