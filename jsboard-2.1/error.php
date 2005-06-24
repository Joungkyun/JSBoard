<?php
include "include/header.php";

$str = urldecode($str);
$str = stripslashes($str);

$title = $notice ? $_('er_msg') : $_('er_msgs');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_('charset')?>">
<title>JSboard <?=$version?> - <?=$title?></title>
<link rel="stylesheet" type="text/css" href="./theme/<?=$print['theme']?>/default.css">
</head>

<body>

&nbsp;<br>
<table align="center" width="95%" cellpadding="1" cellspacing="0"><tr><td class="err_bg">
<table width="100%" cellpadding="3" cellspacing="0">
<tr>
  <td class="err_fg">
    <?=$str?>
    <br><br>
  </td>
</tr><tr>
  <td align="right">
    <form action="<?=$_pself?>"><input type="button" value= "<?=$_('b_sm')?>" onClick = "window.close()"></form>
  </td>
</tr>
</table>
</td></tr></table>

</body>
</html>
