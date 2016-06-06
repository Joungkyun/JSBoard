<?
// 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
// intval - 변수를 정수형으로 변환함
//          http://www.php.net/manual/function.intval.php
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

// file의 내용을 변수로 가져오는 함수
function get_file($filename) {
  $fp = fopen($filename,"r");
  $getfile = fread($fp, filesize($filename));
  fclose($fp);

  return $getfile;
}

?>
