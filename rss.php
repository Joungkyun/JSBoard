<?php
# JSboard RSS Feed
#

# header file 삽입
#
if ( ! @file_exists ("include/header.php") ) {
  echo "<script>\nalert('Don\'t exist header file');\n" .
       "history.back();\nexit;\n</script>\n";
  exit();
} else {
  include 'include/header.php';
}

# RSS 사용 여부 체크 (user config file 로 부터)
#
if ( ! $rss['use'] ) {
  echo "Don't rss support on this board\n";
  exit();
}

# rss 에서 출력할 리스트의 수를 지정
#
$rss['default_article_number'] = '30';

# 게시판 주소
#
$rss['link'] = $board['path'] . "list.php?table=" . $table;

if (!$no)
  $no = $rss['default_article_number'];

# DB 정보 가져오기
#
sql_connect ($db['server'], $db['user'], $db['pass']);

# Description 정보가 있게 할 것인지 아닌지 여부 체크 (config file 에서 체크)
#
if ($rss['is_des'])
  $sql = "select no, date, name, rname, title, text from {$table} order by date DESC limit {$no}";
else
  $sql = "select no, date, name, rname, title from {$table} order by date DESC limit $no";

$result = sql_db_query ($db['name'], $sql);
$i = 0;

while( $rss_article[$i] = sql_fetch_array($result) ) {
  $rss_article[$i]['link'] = "{$board['path']}read.php?table={$table}&no={$rss_article[$i]['no']}";
  $rss_article[$i]['text'] = "<span style=\"font-size:11px;\"><pre>{$rss_article[$i]['text']}</pre></span>";
  $rss_article[$i]['date'] = date("Y-m-d",$rss_article[$i]['date'] ) . 'T' . date("H:i:sO", $rss_article[$i]['date']);

  if (!$rss_article[$i]['name'])
    $rss_article[$i]['name'] = $rss_article[$i]['rname'];

  $i++;
}

$rss_article_num = $i;
unset($result, $i);
mysql_close();

# 여기 까지 이상이 없으면, 실제 RSS 출력
#
header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"{$langs[charset]}\"?>\n";
?>
<rdf:RDF xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://purl.org/rss/1.0/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wiki="http://purl.org/rss/1.0/modules/wiki/">
  <channel rdf:about="<?=$rss['link']?>">
    <title><?=$rss['channel']?></title>
    <link><?=$rss['link']?></link>
    <description><?=$board['title']?></description>
    <items>
     <rdf:Seq>
<?php
for ($i = 0;  $i < $rss_article_num ; $i ++){
	$rss_article[$i]['link'] = str_replace("&", "&amp;", $rss_article[$i]['link']);
?>
      <rdf:li rdf:resource="<?=$rss_article[$i]['link']?>" /> 
<?php
}
?>
     </rdf:Seq>
    </items>
   </channel>
<?php
for ($i = 0 ; $i < $rss_article_num; $i ++){
?>
<item rdf:about="<?=$rss_article[$i]['link']?>">
 <title><?=$rss_article[$i]['title']?></title> 
 <link><?=$rss_article[$i]['link']?></link> 
<?php
	if ($rss['is_des']) {
?>
 <description><?=htmlspecialchars($rss_article[$i]['text'])?></description>
<?php
	}
?>
 <dc:date><?=$rss_article[$i]['date']?></dc:date> 
 <dc:contributor>
 <rdf:Description>
 <rdf:value><?=$rss_article[$i]['name']?></rdf:value> 
 </rdf:Description>
 </dc:contributor>
</item>
<?php
}
?>
</rdf:RDF>
