<?php
# $Id: session.php,v 1.12 2014-02-28 21:37:17 oops Exp $
session_start(); // session�� �����Ѵ�.
include_once '../include/variable.php';
require_once '../include/print.php';
parse_query_str();
if ($mode == "login") {
  $_SESSION['mysqlpass'] = $mysqlpass; //���� ����Ѵ�.
  header("Location: install.php?langss=$langss");
  exit;
} elseif ($mode == "logout") {
  unset ($_SESSION['mysqlpass']); // ������ ����
  header("Location: auth.php?mode=first&langss=$langss");
  exit;
} elseif ($mode == "first") {
  unset ($_SESSION['mysqlpass']); // ������ ����
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
