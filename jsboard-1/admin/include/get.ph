<?
# 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
# intval - 변수를 정수형으로 변환함
#          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs;

  # 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  $agent = get_agent();

  # 윈도우용 네스케이프
  if($agent[br] == "MOZL") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # 한글판
      else {
        if ($langs[code] == "ko") $size *= 2.6;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; # 한글판
      else $size *= 1.3;
    } elseif($agent[os] == "LINUX") $size *= 1.0;
  }

  # 네스케이프 6
  if($agent[br] == "MOZL6") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; # 한글판
      else {
        if ($langs[code] == "ko") $size *= 2.3;
        else $size *= 1.4;
      }
    } else $size *= 1.3;
  }

  # 인터넷 익스플로러
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
