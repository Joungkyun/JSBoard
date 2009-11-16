<?php
# $Id: locate.php,v 1.2 2009-11-16 21:52:45 oops Exp $
include "include/header.php";

$go = $o['go'];
$url = search2url ($o);
$url = str_replace ('&amp;', '&', $url);

switch($go) {
  case "p":
    Header("Location: list.php?table=$table&page={$o['no']}$url");
    break;
  case "n":
    Header("Location: read.php?table=$table&num={$o['no']}$url");
    break;
}
?>
