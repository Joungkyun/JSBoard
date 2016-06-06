<?
// ���� ������ IP address�� ����
$remotes = getenv("REMOTE_ADDR") ;

function get_hostname() {

    // ����ġ ȯ�� ������ REMOTE_ADDR���� �������� IP�� ������
    $host  = getenv('REMOTE_ADDR');
//    $hostname = gethostbyaddr($host);
    // httpd.conf���� HostnameLookup On ���� �������� ��츸 �ش��
    $hostname = getenv('REMOTE_HOST');

    if ($hostname)
      $host = $hostname ;

    return $host;
}

/* �н����� �� �Լ� */
function check_passwd($passwd, $no)
{
    global $table;

    $result  = mysql_query("SELECT passwd FROM $table WHERE no = $no");
    $cpasswd = mysql_result($result, 0, "passwd");
    
    if (crypt($passwd, $cpasswd) == $cpasswd) {
	return 1;
    }
}

/* URL üũ �Լ� */
function check_url($url)
{
    if (!eregi("[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9\-\.]+.*", $url)) {
	return;
    }
    /* �ѱ��� ���ԵǾ����� üũ */
    for($i = 1; $i <= strlen($url); $i++) {
	if ((Ord(substr("$url", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }
    $url = eregi_replace("^http.*://", "", $url);
    $url = eregi_replace("^", "http://", $url);

    return $url;
}

/* �̸��� �ּ� üũ �Լ� */
function check_email($email)
{
    if (!eregi("^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]", $email)) {
	return;
    }
    /* �ѱ��� ���ԵǾ����� üũ */
    for($i = 1; $i <= strlen($email); $i++) {
	if ((Ord(substr("$email", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }

    return $email;
}

/* agent check */
function g_agent($test = "0")
{
  $agent = getenv("HTTP_USER_AGENT");

  if (ereg("^Lynx", $agent)) {
      $agent = "lynx";
  } else if (ereg("MS", $agent)) {
      $agent = "msie";
  } else if (ereg("^Moz", $agent)) {
      if (ereg("Linux", $agent)) {
	$agent = "moz";
      } else if (ereg("WinNT", $agent)) {
	if (ereg("\[ko\]", $agent)) {
	  $agent = "moz_nt_ko";
	} else if (ereg("\[en\]", $agent)) {
	  $agent = "moz_nt_en";
	} else {
	  $agent = "moz";
	}
      } else if (ereg("Win", $agent)) {
	if (ereg("\[ko\]", $agent)) {
	  $agent = "moz_w_ko";
	} else if (ereg("\[en\]", $agent)) {
	  $agent = "moz_w_en";
	} else {
	  $agent = "moz";
	}
      } else {
	$agent = "moz";
      }
  } else {
      $agent = "unknown";
  }
  
  if ($test) {
    if ($test == $agent) {
      return 1;
    } else {
      return 0;
    }
  }

  return $agent;
}

/* SQL query time ���� */
function get_microtime($old, $new)
{
     // �־��� ���ڿ��� ���� (sec, msec���� ��������) 
    $old = explode( " ", $old);
    $new = explode( " ", $new);

    $time[msec] = $new[0] - $old[0];
    $time[sec]  = $new[1] - $old[1];

    if($time[msec] < 0) {
    $time[msec] = 1.0 + $time[msec];
    $time[sec]--;
    }

    $time = sprintf( "%.2f", $time[sec] + $time[msec]);

    return $time;
}

// ���ڿ��� �ѱ��� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
//
// ord    - ������ ASCII ���� ������
//          http://www.php.net/manual/function.ord.php3
function is_hangul($char)
{
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

function mode_check ($up,$op,$sp) {

  global $nopass, $pass_alert ;

  if (!$up) {
    echo("<script>\n" .
            "alert(\"$nopass\")\n" .
            "history.back()\n" .
            "</script>\n") ;
     exit ;
  } else {
    if ($up != $op && $up != $sp) {
      echo("<script>\n" .
              "alert(\"$pass_alert\")\n" .
              "history.back()\n" .
              "</script>\n") ;
       exit ;
    }
  }
}

?>
