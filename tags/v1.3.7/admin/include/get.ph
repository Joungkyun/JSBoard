<?
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
