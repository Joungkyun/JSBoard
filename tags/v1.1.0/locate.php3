<?
require("include/header.ph");

$url = search2url($o);

switch($o[go]) {
  case "p":
    Header("Location: list.php3?table=$table&page=$o[no]$url");
    break;
  case "n":
    Header("Location: read.php3?table=$table&num=$o[no]$url");
    break;
}
?>
