<?php
# get 등으로 넘겨오는 변수
# $table - 테이블지정
# $no - 가져오는 글 갯수

# table을 지정하지 않았을 경우 대입하는 게시판 이름. 
# JSBoard 생성시 같이 생성되는 게시판이 test인지라 test라 해둠.
# 주로 사용하는 게시판(예를들면 free)이 있다면 그것으로 바꿔주는 것이 좋겠지요?

/*
 *---------[db 정보 include]------------------
 *$db['server']      # DB address
 *$db['user']        # DB login user
 *$db['pass']        # DB login pass
 *$db['name']        # DB name
 *
 */

# global 설정 파일 체크
if (!@file_exists('config/global.ph')) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
  exit();
} else {
  include_once 'config/global.ph';
}

# table 설정 파일 체크
if (!@file_exists("data/{$table}/config.ph")) {
  echo "<script>\nalert('Don\'t exist {$table}\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
  exit();
} else {
  include_once "data/{$table}/config.ph";
}

if ( ! $rss['use'] ) {
  echo "Don't rss support on this board\n";
  exit();
}

$rss['default_table'] = 'test';
$rss['default_article_number'] = '30'; # no를 지정해주지 않는다면 default 값을 쓰게 됩니다.

if ( !$table ) {
  if ($rss['default_table']) {
    $table = $rss['default_table'];
  } else {
    echo "<script>\nalert('no table name');\n" .
         "history.back();\nexit;\n</script>\n";
    exit();
  }
}

$rss['link'] = $board['path'] . "list.php&amp;table=" . $table;	# 게시판 주소

if (!$no)
  $no = $rss['default_article_number'];

/*----------[db 접속, 쿼리날리기]-------------------------*/
mysql_connect($db['server'], $db['user'], $db['pass']);
mysql_select_db($db['name']);

if ($rss['is_des'])
  $sql = "select no, date, name, rname, title, text from {$table} order by date DESC limit {$no}";
else
  $sql = "select no, date, name, rname, title from {$table} order by date DESC limit $no";

$result = mysql_query($sql);
$i = 0;

while( $rss_article[$i] = mysql_fetch_array($result) ) {
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

// 디버깅 코드. 내용 fetch 한 것 뿌려보기.
//echo "<pre>";
//print_r($rss_article);
//echo "</pre>";
//exit();
//--------------[이상이 없으면 출력]-----------------------

include ("include/header.ph");

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
