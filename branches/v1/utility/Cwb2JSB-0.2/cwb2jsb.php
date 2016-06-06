#!/usr/bin/php4 -q
<?
# SCRIPTED BY JoungKyun KIm <http://www.oops.org>
# This Program follows GPL License.

$table = "";
$dbuser = "";
$dbpass = "";
$dbaddr = "";
$dbname = "";

$dirs = "DATA";

$con = mysql_connect($dbaddr,$dbuser,$dbpass);
mysql_select_db($dbname,$con);

# table 이름이 있으면 삭제
$sql = "DROP TABLE $table";
$result = mysql_query($sql,$con);

$sql = "CREATE TABLE $table (
                  no int(6) DEFAULT '0' NOT NULL auto_increment,
                  num int(6) DEFAULT '0' NOT NULL,
                  idx int(6) DEFAULT '0' NOT NULL,
                  date int(11) DEFAULT '0' NOT NULL,
                  host tinytext,
                  name tinytext,
                  passwd varchar(13),
                  email tinytext,
                  url tinytext,
                  title tinytext,
                  text mediumtext,
                  refer int(6) DEFAULT '0' NOT NULL,
                  reyn int(1) DEFAULT '0' NOT NULL,
                  reno int(6) DEFAULT '0' NOT NULL,
                  rede int(6) DEFAULT '0' NOT NULL,
                  reto int(6) DEFAULT '0' NOT NULL,
                  html int(1) DEFAULT '1' NOT NULL,
                  moder int(1) DEFAULT '0' NOT NULL,
                  bofile varchar(100),
                  bcfile varchar(100),
                  bfsize int(4),
                  convno int(6) DEFAULT '0' NOT NULL,
                  KEY no (no),
                  KEY num (num),
                  KEY idx (idx),
                  KEY reno (reno),
                  KEY date (date),
                  KEY reto (reto),
                  PRIMARY KEY (no))";

$create_table = mysql_query($sql,$con);

$p = opendir($dirs);
while($i = readdir($p)) {
  if(preg_match("/^[0-9]+\.txt/i",$i)) {
    $n = preg_replace("/(^[0-9]+)\.txt/i","\\1",$i);
    $files[] = $i;
  }
}
closedir($p);

$fno = sizeof($files);
sort($files);

$passwd = crypt("1423");
$moder = 0;
$reyn = 0;

for($i=0;$i<$fno;$i++) {

  $fn = "$dirs/$files[$i]";
  $fsize = filesize($fn);
  $f_no = preg_replace("/^1[0]*([0-9]+)\.txt/i","\\1",$files[$i]);
  $p = fopen($fn,"r");

  $convno = $f_no;

  while(!feof($p)) {
    $fc = fgets($p,$fsize);

    # Date
    if(preg_match("/^$f_no\.Date/mi",$fc)) {
      $date = trim(preg_replace("/^$f_no\.Date=/i","",$fc));
      $y = substr($date,0,4);
      $m = substr($date,4,2);
      $d = substr($date,6,2);
      $h = substr($date,8,2);
      $mm= substr($date,10,2);
      $s = substr($date,12,2);

      $date = mktime($h,$mm,$s,$m,$d,$y);
    }

    # host
    if(preg_match("/^$f_no\.Domain=/mi",$fc)) {
      $host = trim(preg_replace("/^$f_no\.Domain=/i","",$fc));
      $host = str_replace("\'","'",$host);
      $host = str_replace("'","\'",$host);
    }

    # Name
    if(preg_match("/^$f_no\.Name=/mi",$fc)) {
      $name = trim(preg_replace("/^$f_no\.Name=/i","",$fc));
      $name = str_replace("\'","'",$name);
      $name = str_replace("'","\'",$name);
    }

    # email
    if(preg_match("/^$f_no\.Email=/mi",$fc)) {
      $email = trim(preg_replace("/^$f_no\.Email=/i","",$fc));
      $email = str_replace("\'","'",$email);
      $email = str_replace("'","\'",$email);
    }

    # access
    if(preg_match("/^$f_no\.Access=/mi",$fc)) {
      $refer = trim(preg_replace("/^$f_no\.Access=/i","",$fc));
      $refer = str_replace("\'","'",$refer);
      $refer = str_replace("'","\'",$refer);
    }

    # html
    if(preg_match("/^$f_no\.ContentType=/mi",$fc)) {
      $htmls = trim(preg_replace("/^$f_no\.ContentType=/i","",$fc));
      if($htmls == "text/html") $html = 1;
      else $html = 0;
    }

    # title
    if(preg_match("/^$f_no\.Subject=/mi",$fc)) {
      $title = trim(preg_replace("/^$f_no\.Subject=/i","",$fc));
      $title = str_replace("\'","'",$title);
      $title = str_replace("'","\'",$title);
    }

    # context
    if(preg_match("/^$f_no\.Text=/mi",$fc)) {
      $context = preg_replace("/^$f_no\.Text=/i","",$fc);
      $context = str_replace("<br>","\n",$context);
      $context = str_replace("<p>","\n\n",$context);
      $context = str_replace("%0a","\n",$context);
      $context = str_replace("\'","'",$context);
      $context = str_replace("'","\'",$context);
      $context = chop(wordwrap($context,"80"));
    }

    # thread
    if(preg_match("/^$f_no\.Thread=/i",$fc)) {
      $thread = trim(preg_replace("/^$f_no\.Thread=/i","",$fc));
      if($thread > 0) { 
        $sql = "UPDATE $table set reyn=1 WHERE convno=$thread";      
        $result = mysql_query($sql,$con);

        $csql = "SELECT no,reto,reno,rede,idx from $table WHERE convno=$thread";
        $result = mysql_query($csql,$con);
        $row = mysql_fetch_array($result);
        $reno = $row[no];
        $reto = !$row[rede] ? $row[no] : $row[reto];
        $rede = $row[rede]+1;

        $sql = "UPDATE $table SET idx = idx + 1 WHERE (idx + 0) >= $row[idx]";
        $result = mysql_query($sql,$con);
        $idx = $row[idx];
        $num_check = 1;
      } else {
        $reno = 0;
        $reto = 0;
        $rede = 0;
	$idx = 0;
      }
    }
  }

  $sql = "SELECT MAX(num) AS num, MAX(idx) AS idx from $table";
  $result = mysql_query($sql,$con);
  $max = mysql_fetch_array($result);

  if($num_check) $num = 0;
  else $num = $max[num]+1;

  $idx = $idx ? $idx : $max[idx] + 1;

  echo "$i : $f_no : $convno\n";


  $sql = "INSERT INTO $table VALUES('','$num','$idx','$date','$host','$name','$passwd',
                 '$email','$url','$title','$context','$refer','$reyn','$reno','$rede',
                 '$reto','$html','$moder','','','','$convno')";
  $result = mysql_query($sql,$con);

  $num_check = 0;
}

$sql = "ALTER TABLE $table DROP convno";
$result = mysql_query($sql,$con);
mysql_close($con);
?>
