<?
// 웹 서버 접속자의 IP 주소 혹은 도메인명을 가져오는 함수
//
// getenv        - 환경 변수값을 가져옴
//                 http://www.php.net/manual/function.getenv.php
// gethostbyaddr - IP 주소와 일치하는 호스트명을 가져옴
//                 http://www.php.net/manual/function.gethostbyaddr.php
function get_hostname($r = 0) {
  # 아파치 환경 변수인 REMOTE_ADDR에서 접속자의 IP를 가져옴
  $ip    = getenv("REMOTE_ADDR");
  $check = $r ? @gethostbyaddr($ip) : "";
  $host  = $check ? $check : $ip;

  return $host;
}

// 접속한 사람이 사용하는 브라우져를 알기 위해 사용되는 함수, 현재는 FORM
// 입력창의 크기가 브라우져마다 틀리게 설정되는 것을 보정하기 위해 사용됨
//
// getenv - 환경 변수값을 가져옴
//          http://www.php.net/manual/function.getenv.php
function get_agent() {
  $agent_env = getenv("HTTP_USER_AGENT");

  // $agent 배열 정보 [br] 브라우져 종류
  //                  [os] 운영체제
  //                  [ln] 언어 (넷스케이프)
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

// 오늘 자정을 기준으로 UNIX_TIMESTAMP의 형태로 시각을 뽑아오는 함수
//
// time    - 현재 시각의 UNIX TIMESTAMP를 가져옴
//           http://www.php.net/manual/function.time.php
// date    - UNIX TIMESTAMP를 지역 시간에 맞게 지정한 형식으로 출력
//           http://www.php.net/manual/function.date.php
// mktime  - 지정한 시각의 UNIX TIMESTAMP를 가져옴
//           http://www.php.net/manual/function.mktime.php
// explode - 구분 문자열을 기준으로 문자열을 나눔
//           http://www.php.net/manual/function.explode.php
function get_date() {
  // 현재의 시간을 $time에 저장
  $time  = time();
  // 년, 월, 일을 각각의 변수에 대입
  $date  = date("m:d:Y", $time);
  $date = explode(":", $date);

  // 오늘 날짜에 자정을 기준으로 UNIX_TIMESTAMP 형식으로 만듬
  $today = mktime(0, 0, 0, $date[0], $date[1], $date[2]);

  return $today;
}


// PHP의 microtime 함수로 얻어지는 값을 비교하여 경과 시간을 가져오는 함수
//
// explode - 구분 문자열을 기준으로 문자열을 나눔
//           http://www.php.net/manual/function.explode.php
function get_microtime($old, $new) {
  // 주어진 문자열을 나눔 (sec, msec으로 나누어짐)
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
    
// 넷스케이프와 익스간의 FORM 입력창의 크기 차이를 보정하기 위한 함 수
// intval - 변수를 정수형으로 변환함
//          http://www.php.net/manual/function.intval.php
function form_size($size, $print = 0) {
  global $langs;

  // 클라이언트 브라우져 종류를 가져오는 함수 (include/get_info.ph)
  $agent = get_agent();

  // 윈도우용 네스케이프
  if($agent[br] == "MOZL") {
    if($agent[os] == "NT") {
      if($agent[ln] == "KO") $size *= 1.1; // 한글판
      else {
        if ($langs[code] == "ko") $size *= 2.3;
        else $size *= 1.4;
      }
    } else if($agent[os] == "WIN") {
      if($agent[ln] == "KO") $size *= 1.1; // 한글판
      else $size *= 1.3;
    }
  }
  // 인터넷 익스플로러  echo "## $agnet[os] ##";
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

// file의 내용을 변수로 가져오는 함수
function get_file($filename) {
  $fp = fopen($filename,"r");
  $getfile = fread($fp, filesize($filename));
  fclose($fp);

  return $getfile;
}

?>
