#!/usr/bin/php4 -q
<?
set_time_limit(0);

# 변환할 file type ( ascii or gdbm 중 선택)
$trans[type] = "";

# 변환할 gdbm file 이름
$trans[file] = "";

# 변환된 파일이 생성될 디렉토리 경로
$trans[path] = "./DATA";

# perl의 절대 경로를 적어준다.
$perl = "/usr/bin/perl";

######## Function Part ######################

function file_name($f) {
  $len = strlen($f);
  if($len == 7) $add = 1;
  elseif($len == 6) $add = 10;
  elseif($len == 5) $add = 100;
  elseif($len == 4) $add = 1000;
  elseif($len == 3) $add = 10000;
  elseif($len == 2) $add = 100000;
  elseif($len == 1) $add = 1000000;

  $fn = $add.$f.".txt";
  return $fn;
}

function get_microtime($old, $new) {
  # 주어진 문자열을 나눔 (sec, msec으로 나누어짐)
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

######## Function Part ######################

# script 시작 시간 측정
$c_time[] = microtime();

# data directory에 있는 파일들을 삭제
exec("rm -rf $trans[path]/*");

if($trans[type] == "gdbm") {
  $dbm = dba_open($trans[file],"r",$trans[type]);
  $key = dba_firstkey($dbm);

  while($key != false) {
    $handle[] = $key;
    $key = dba_nextkey($dbm);
  }

  for($i=0;$i<count($handle);$i++) {
    if(eregi("^[0-9]",$handle[$i])) {
      # Crazy WWW Board의 글번호를 얻음
      $no = trim(eregi_replace("(^[0-9]+)\.(.+)","\\1",$handle[$i]));

      # Crazy WWW Board의 key name을 얻음
      $var = trim($handle[$i]);

      # 각 key들의 값을 얻음
      if(eregi("Text",$handle[$i])) {
        $context = chop(dba_fetch($handle[$i],$dbm));
        $context = eregi_replace("\r\n","%0a",$context);
      } else {
        $context = trim(dba_fetch($handle[$i],$dbm));
      }

      $text = "$var=$context\n";

      $p = fopen("temp.trans","wb");
      fwrite($p,$text);
      fclose($p);

      $filename = file_name($no);

      if(!file_exists("$trans[path]/$filename")) $f[e] = 0;
      else $f[e] = 1;

      exec("cat temp.trans >> $trans[path]/$filename");
      unlink("temp.trans");
      echo "$no      :      $f[e] :  $filename\n";
    }
  }

  dba_close($dbm);
} elseif($trans[type] == "ascii") {
  # ascii file의 ^M값을 %0a 로 치환
  exec("$perl -p -i -e 's/\\r\\n/%0a/' $trans[file]");
  $p = fopen($trans[file],"rb");
  while(!feof($p)) {
    $text = fgets($p,30000);
    if(eregi("^[0-9]",$text)) {
      $context = str_replace("\\'","'",$text);
      $context = str_replace("'","\\'",$text);
      $context = str_replace("\\\"","\"",$context);
      $context = str_replace("\"","\\\"",$context);
      $context = chop($context);
      $c_no = eregi_replace("(^[0-9]+)\.[a-z]{1}(.*)","\\1",$text);
      $filename = file_name($c_no);

      echo "$c_no  :";

      $wc=fopen("temp.trans","wb");
      fwrite($wc,$context."\n");
      fclose($wc);

      if(!file_exists("$trans[path]/$filename"))  {
        exec("touch $trans[path]/$filename");
        echo "  0  :";
      } else echo "  1  :";
      exec("cat temp.trans >> $trans[path]/$filename");
      echo "  $filename\n";
      unset($text);
      unset($context);

      unlink("temp.trans");
    } else unset($text);

  }
  fclose($p);
} else echo "변형할 type을 설정해 주십시오";

$c_time[] = microtime();
$time = get_microtime($c_time[0], $c_time[1]);

echo "-------------------------------------------\n".
     " 소요시간                      $time sec\n".
     "-------------------------------------------\n";

?>
