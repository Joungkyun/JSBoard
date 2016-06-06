<?php
# JSboard RSS Feed
# $Id: rss.php,v 1.27 2014/03/02 17:11:28 oops Exp $
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
$c = sql_connect ($db['server'], $db['user'], $db['pass']);
sql_select_db ($db['name'], $c);

# Description 정보가 있게 할 것인지 아닌지 여부 체크 (config file 에서 체크)
#
if ($rss['is_des'])
  $sql = "select no, date, name, rname, title, text from {$table} order by date DESC limit {$no}";
else
  $sql = "select no, date, name, rname, title from {$table} order by date DESC limit $no";

$result = sql_query ($sql, $c);
$i = 0;

while( $rss_article[$i] = sql_fetch_array($result,$c) ) {
  $rss_article[$i]['title'] = htmlspecialchars ($rss_article[$i]['title'], ENT_QUOTES);
  $rss_article[$i]['link'] = "{$board['path']}read.php?table={$table}&amp;no={$rss_article[$i]['no']}";
  $rss_article[$i]['date'] = date ("r", $rss_article[$i]['date']);
  #$rss_article[$i]['date'] = date("Y-m-d",$rss_article[$i]['date'] ) . 'T' . date("H:i:sO", $rss_article[$i]['date']);

  if ( $rss['is_des'] ) {
    #$rss_article[$i]['text'] = preg_replace ("!\n!", "<br />\n", $rss_article[$i]['text']);
    $rss_article[$i]['text'] = preg_replace ("!^[([0-9]+;[0-9]+m)?!", "", $rss_article[$i]['text']);
    $rss_article[$i]['text'] = htmlspecialchars ($rss_article[$i]['text']);
    $rss_article[$i]['text'] = auto_link ($rss_article[$i]['text']);

    $_body = "<table width=\"100%\" border=0 cellpadding=0 cellspacing=1>\n" .
             "<tr><td bgcolor=#000000>\n" .
             "<table width=\"100%\" border=0 cellpadding=3 cellspacing=1>\n" .
             "<tr><td style=\"font: 12px 굴림체; font-weight: bold; color: #ffffff\">\n" .
             "REPLY : <a href={$board['path']}reply.php?table={$table}&no={$rss_article[$i]['no']} style=\"color: #ffffff; text-decoration: none;\">" .
             "{$board['path']}reply.php?table={$table}&no={$rss_article[$i]['no']}</a><br />\n" .
             "DELETE: <a href={$board['path']}delete.php?table={$table}&no={$rss_article[$i]['no']} style=\"color: #ffffff; text-decoration: none;\">" .
             "{$board['path']}delete.php?table={$table}&no={$rss_article[$i]['no']}</a>\n" .
             "</td></tr>\n" .
             "</table>\n" .
             "</td></tr>\n" .
             "<tr><td bgcolor=#000000>\n" .
             "<table width=\"100%\" border=0 cellpadding=3 cellspacing=1>\n" .
             "<tr><td bgcolor=#ffffff style=\"font-size: 11px;\"><pre>\n" .
             "{$rss_article[$i]['text']}\n" .
             "</pre></td></tr>\n" .
             "</table>\n" .
             "</td></tr>\n" .
             "</table>\n";

    $_body = htmlspecialchars ($_body, ENT_QUOTES);
    $rss_article[$i]['text'] = $_body;
  }

  if (!$rss_article[$i]['name'])
    $rss_article[$i]['name'] = $rss_article[$i]['rname'];

  $i++;
}

$rss_article_num = $i;
unset($result, $i);
sql_close($c);

$now = time ();
$cYear = date ("Y", $now);
$bdate = date ("r", $now);
$_charset = strtolower ($langs['charset']);

$gotoUTF8 = check_utf8_conv ($_charset);
$cset = $gotoUTF8 ? 'utf-8' : $_charset;

# 여기 까지 이상이 없으면, 실제 RSS 출력
#
header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: text/xml');

utf8_fallback ($rss['channel'], $_charset);
utf8_fallback ($board['title'], $_charset);

echo "<?xml version=\"1.0\" encoding=\"{$cset}\"?>\n";
?>
<rss version="2.0"
  xml:base="<?php echo $board['path']?>"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <atom:link href="http://<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']?>" rel="self" type="application/rss+xml" />
    <title><?php echo $rss['channel']?></title>
    <link><?php echo $rss['link']?></link>
    <description><?php echo $board['title']?></description>
    <language><?php echo $langs['code']?></language>
    <copyright>1999-<?php echo $cYear?> JSBoard Open Project</copyright>
    <lastBuildDate><?php echo $bdate?></lastBuildDate>
    <generator>JSBoard <?php echo $board['ver']?></generator>

<?php
for ( $i=0; $i<$rss_article_num; $i++ ) {
  utf8_fallback ($rss_article[$i], $_charset);
  echo "<item>\n" .
       "  <title>{$rss_article[$i]['title']}</title>\n" .
       "  <link>{$rss_article[$i]['link']}</link>\n" .
       "  <guid>{$rss_article[$i]['link']}</guid>\n";

  if ( $rss['is_des'] ) {
    echo "  <description>{$rss_article[$i]['text']}</description>\n";
  }

  echo "  <pubDate>{$rss_article[$i]['date']}</pubDate>\n" .
       "  <dc:creator>{$rss_article[$i]['name']}</dc:creator>\n" .
       "</item>\n";
}
?>

  </channel>
</rss>
