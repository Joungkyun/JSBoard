<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- =============================== A foreword =============================== -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_('charset')?>">
<?
switch ($path['type']) {
  case 'user_admin' :
    $csspath = '../..';
    break;
  case 'admin' :
    $csspath = '..';
    break;
  default :
    $csspath = '.';
}

 if ( ! preg_match ('/admin/i', $file_lo) ) $_title = get_title();
 else  $_title = $sub_title;
?>
<title>JSBoard Administration Center [ <?=$_title?> ]</title>
<link rel="stylesheet" type="text/css" href="<?=$csspath?>/theme/<?=$print['theme']?>/default.css">
<STYLE TYPE="text/css">
<!--
a:link, a:visited, a:active { text-decoration: none; color:<?=$color['text']?> }
a:hover { text-decoration: underline; color:<?=$color['text']?> }
body, td { font: 12px <?=$_('font')?>; color:<?=$color['text']?>; }
input {font: 9pt <?=$_('font')?>; background-color:<?=$color['b_bg']?>; color:<?=$color['text']?>; border:1x solid <?=$color['n1_fg']?>}
textarea {font: 10pt <?=$_('font')?>; background-color:<?=$color['l4_bg']?>; color:<?=$color['l4_fg']?>; border:1x solid <?=$color['l4_gu'] ?>}
form { display: inline; }
 #radio {font: 9pt <?=$_('font')?>; background-color:<?=$color['bgcol']?>; color:<?=$color['l0_bg']?>; border:1x solid <?=$color['bgcol'] ?>}
 #title {font:20pt <?=$_('font')?>; color:<?=$color['n0_bg']?>}
-->
</style>
</head>

<body bgcolor="<?=$color['b_bg']?>">

<?php
if($table) {
  table_name_check ($table);
  if ( file_exists ("data/{$table}/html_head.php") ) {
    require_once "data/{$table}/html_head.php";
  }
}
?>
<!-- =============================== A foreword =============================== -->
