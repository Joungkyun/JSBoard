<?php
# $Id: locate.php,v 1.7 2009-11-19 05:29:49 oops Exp $
include "include/header.php";

$go = $o['go'];
$url = search2url($o);
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
