<?php
include "include/header.ph";

$go = $o['go'];
$url = search2url($o);

switch($go) {
  case "p":
    Header("Location: list.php?table=$table&page={$o['no']}$url");
    break;
  case "n":
    Header("Location: read.php?table=$table&num={$o['no']}$url");
    break;
}
?>
