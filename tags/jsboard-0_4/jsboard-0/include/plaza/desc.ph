<?
//include("./include/header.phtml");
//include("./include/menu.phtml");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-KR">
<title>게시판입니다. <? echo $sub_title ?></title>
<style type="text/css">
<!--
a:link, a:visited, a:active { text-decoration: none; }
a:hover { text-decoration:underline; }
td { font-size: 10pt; }
-->
</style>
</head>

<!--
<body bgcolor="#445566" text="white" link="white" vlink="white">
-->
<body bgcolor="white" text="black" link="blue" vlink="">

<? if(!$ndesc) : ?>

<!-- 글쓰기, 답장쓰기등의 화면에 나올 글을 씁니다. -->

<? endif ?>

<br>
