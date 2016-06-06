#!/usr/bin/php4 -q
<?
# SCRIPTED BY JoungKyun KIm <http://www.oops.org>
# This Program follows GPL License.

$table = "";
$dbuser = "";
$dbpass = "";
$dbaddr = "";
$dbname = "";

$dirs = "Data";

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
  if(eregi("^[0-9]+\.txt",$i)) {
    $n = eregi_replace("(^[0-9]+)\.txt","\\1",$i);
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
  $f_no = eregi_replace("^1[0]*([0-9]+)\.txt","\\1",$files[$i]);
  $p = fopen($fn,"r");

  $convno = $f_no;

  while(!feof($p)) {
    $fc = fgets($p,$fsize);

    # Date
    if(eregi("$f_no\.Date",$fc)) {
      $date = trim(eregi_replace("$f_no\.Date=","",$fc));
      $y = substr($date,0,4);
      $m = substr($date,4,2);
      $d = substr($date,6,2);
      $h = substr($date,8,2);
      $mm= substr($date,10,2);
      $s = substr($date,12,2);

      $date = mktime($h,$mm,$s,$m,$d,$y);
    }

    # host
    if(eregi("$f_no\.Domain=",$fc)) {
      $host = trim(eregi_replace("$f_no\.Domain=","",$fc));
    }

    # Name
    if(eregi("$f_no\.Name=",$fc)) {
      $name = trim(eregi_replace("$f_no\.Name=","",$fc));
    }

    # email
    if(eregi("$f_no\.Email=",$fc)) {
      $email = trim(eregi_replace("$f_no\.Email=","",$fc));
    }

    # access
    if(eregi("$f_no\.Access=",$fc)) {
      $refer = trim(eregi_replace("$f_no\.Access=","",$fc));
    }

    # html
    if(eregi("$f_no\.ContentType=",$fc)) {
      $htmls = trim(eregi_replace("$f_no\.ContentType=","",$fc));
      if($htmls == "text/html") $html = 1;
      else $html = 0;
    }

    # title
    if(eregi("$f_no\.Subject=",$fc)) {
      $title = trim(eregi_replace("$f_no\.Subject=","",$fc));
    }

    # context
    if(eregi("$f_no\.Text=",$fc)) {
      $context = eregi_replace("$f_no\.Text=","",$fc);
      $context = eregi_replace("<br>","\n",$context);
      $context = eregi_replace("<p>","\n\n",$context);
      $context = eregi_replace("%0a","\n",$context);
      $context = chop(wordwrap($context,"80"));
    }

    # thread
    if(eregi("$f_no\.Thread=",$fc)) {
      $thread = trim(eregi_replace("$f_no\.Thread=","",$fc));
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


  $sql = "INSERT INTO $table VALUES('',$num,$idx,$date,'$host','$name','$passwd',
                 '$email','$url','$title','$context','$refer','$reyn','$reno','$rede',
                 '$reto','$html','$moder','','','','$convno')";
  $result = mysql_query($sql,$con);

  $num_check = 0;
}

$sql = "ALTER TABLE $table DROP convno";
$result = mysql_query($sql,$con);
mysql_close($con);
?>
