<?
# table list 를 구한다.
function get_tblist($db,$t="") {
  $list = mysql_list_tables($db);

  # table list 존재 유무 체크
  table_list_check($db);

  # table 의 총 갯수를 구함
  $list_num = mysql_num_rows($list);
  if(!$j) $j = 0;

  for ($i=0;$i<$list_num;$i++) {
    # table 이름을 구하여 배열에 저장
    $l[$i] = mysql_tablename($list,$i);

    # 배열에 저장된 이름중 알파벳별 구분 변수가 있으면 소트된
    # 이름만 다시 배열에 저장
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
