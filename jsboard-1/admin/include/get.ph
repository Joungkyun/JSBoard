<?
# �ݽ��������� �ͽ����� FORM �Է�â�� ũ�� ���̸� �����ϱ� ���� �� ��
# intval - ������ ���������� ��ȯ��
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs;

  # Ŭ���̾�Ʈ ������ ������ �������� �Լ� (include/get_info.ph)
  $agent = get_agent();

  # ������� �׽�������
  if($agent[br] == "MOZL") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else {
        if ($langs[code] == "ko") $size *= 2.6;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else $size *= 1.3;
    } elseif($agent[os] == "LINUX") $size *= 1.0;
  }

  # �׽������� 6
  if($agent[br] == "MOZL6") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # �ѱ���
      else {
        if ($langs[code] == "ko") $size *= 2.3;
        else $size *= 1.4;
      }
    } else $size *= 1.3;
  }

  # ���ͳ� �ͽ��÷η�
  if($agent[br] == "MSIE") {
    if ($agent[os] == "NT")
      if ($langs[code] == "ko") $size *= 2.3;
      else $size *= 2.6;
    else $size *= 2.3;
  }

  if($agent[br] == "LYNX") $size *= 2;

  $size = intval($size);
  if($print) echo $size;

  return $size;
}

# table list �� ���Ѵ�.
function get_tblist($db,$t="") {
  $list = mysql_list_tables($db);

  # table list ���� ���� üũ
  table_list_check($db);

  # table �� �� ������ ����
  $list_num = mysql_num_rows($list);
  if(!$j) $j = 0;

  for ($i=0;$i<$list_num;$i++) {
    # table �̸��� ���Ͽ� �迭�� ����
    $l[$i] = mysql_tablename($list,$i);

    # �迭�� ����� �̸��� ���ĺ��� ���� ������ ������ ��Ʈ��
    # �̸��� �ٽ� �迭�� ����
    if($t) {
      if(eregi("^$t",$l[$i])) {
        $ll[$j] = $l[$i];
        $j++;
      }
    }
  }

  if($t) return $ll;
  else return $l;
}

?>
