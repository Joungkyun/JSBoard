<?php
# $Id: session.php,v 1.11 2014-02-26 17:50:18 oops Exp $
session_start(); // session을 시작한다.
require_once '../include/print.php';
parse_query_str();
if ($mode == "login") {
  $_SESSION['mysqlpass'] = $mysqlpass; //세션 등록한다.
  header("Location: install.php?langss=$langss");
  exit;
} elseif ($mode == "logout") {
  unset ($_SESSION['mysqlpass']); // 세션을 삭제
  header("Location: auth.php?mode=first&langss=$langss");
  exit;
} elseif ($mode == "first") {
  unset ($_SESSION['mysqlpass']); // 세션을 삭제
  $langs['code'] = ($langss == "ko") ? "ko" : "en";

  $path['type'] = "Install";
  require_once '../include/lang.php';
  $str = str_replace("\n","\\n",$langs['first_acc']);
  $nostr = str_replace("\n","<br>\n",$str);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>JSBoard Initialize</title>
</head>

<body>
  <script type="text/javascript">
    alert('<?php echo $str?>');
    document.location='../login.php?type=admin';
  </script>
  <noscript>
    Message: <?php echo $nostr?><br>
    And If you want to go main administrator's page, <a href="../login.php?type=admin">click here</a>!
  </noscript>
</body>
</html>
