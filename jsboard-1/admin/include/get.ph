<?
// �� ���� �������� IP �ּ� Ȥ�� �����θ��� �������� �Լ�
//
// getenv        - ȯ�� �������� ������
//                 http://www.php.net/manual/function.getenv.php
// gethostbyaddr - IP �ּҿ� ��ġ�ϴ� ȣ��Ʈ���� ������
//                 http://www.php.net/manual/function.gethostbyaddr.php
function get_hostname($r = 0) {
  # ����ġ ȯ�� ������ REMOTE_ADDR���� �������� IP�� ������
  $ip    = getenv("REMOTE_ADDR");
  $check = $r ? @gethostbyaddr($ip) : "";
  $host  = $check ? $check : $ip;

  return $host;
}

// ������ ����� ����ϴ� �������� �˱� ���� ���Ǵ� �Լ�, ����� FORM
// �Է�â�� ũ�Ⱑ ���������� Ʋ���� �����Ǵ� ���� �����ϱ� ���� ����
//
// getenv - ȯ�� �������� ������
//          http://www.php.net/manual/function.getenv.php
function get_agent() {
  $agent_env = getenv("HTTP_USER_AGENT");

  // $agent �迭 ���� [br] ������ ����
  //                  [os] �ü��
  //                  [ln] ��� (�ݽ�������)
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
    } else $agent[os] = "OTHER";
  } else $agent[br] = "OTHER";

  return $agent;
}

// ���� ������ �������� UNIX_TIMESTAMP�� ���·� �ð��� �̾ƿ��� �Լ�
//
// time    - ���� �ð��� UNIX TIMESTAMP�� ������
//           http://www.php.net/manual/function.time.php
// date    - UNIX TIMESTAMP�� ���� �ð��� �°� ������ �������� ���
//           http://www.php.net/manual/function.date.php
// mktime  - ������ �ð��� UNIX TIMESTAMP�� ������
//           http://www.php.net/manual/function.mktime.php
// explode - ���� ���ڿ��� �������� ���ڿ��� ����
//           http://www.php.net/manual/function.explode.php
function get_date() {
  // ������ �ð��� $time�� ����
  $time  = time();
  // ��, ��, ���� ������ ������ ����
  $date  = date("m:d:Y", $time);
  $date = explode(":", $date);

  // ���� ��¥�� ������ �������� UNIX_TIMESTAMP �������� ����
  $today = mktime(0, 0, 0, $date[0], $date[1], $date[2]);

  return $today;
}


// PHP�� microtime �Լ��� ������� ���� ���Ͽ� ��� �ð��� �������� �Լ�
//
// explode - ���� ���ڿ��� �������� ���ڿ��� ����
//           http://www.php.net/manual/function.explode.php
function get_microtime($old, $new) {
  // �־��� ���ڿ��� ���� (sec, msec���� ��������)
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
    
// �ݽ��������� �ͽ����� FORM �Է�â�� ũ�� ���̸� �����ϱ� ���� �� ��
// intval - ������ ���������� ��ȯ��
//          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs;

  // Ŭ���̾�Ʈ ������ ������ �������� �Լ� (include/get_info.ph)
  $agent = get_agent();

  // ������� �׽�������
  if($agent[br] == "MOZL") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; // �ѱ���
      else {
        if ($langs[code] == "ko") $size *= 2.3;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; // �ѱ���
      else $size *= 1.3;
    }
  }
  // ���ͳ� �ͽ��÷η�  echo "## $agnet[os] ##";
  if($agent[br] == "MSIE") {
    if ($agent[os] == "NT")
      if ($langs[code] == "ko") $size *= 2.3;
      else $size *= 2.6;
    else $size *= 2.3;
  }

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

// file�� ������ ������ �������� �Լ�
function get_file($filename) {
  $fp = fopen($filename,"r");
  $getfile = fread($fp, filesize($filename));
  fclose($fp);

  return $getfile;
}

?>
