<?
# $Id: locate.php,v 1.4 2009-11-20 13:56:38 oops Exp $
include "include/header.ph";

$url = search2url($o);

switch($o[go]) {
  case "p":
    Header("Location: list.php?table=$table&page=$o[no]$url");
    break;
  case "n":
    Header("Location: read.php?table=$table&num=$o[no]$url");
    break;
}
?>
