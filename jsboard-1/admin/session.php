<?
session_start(); // session�� �����Ѵ�.
if ($mode == "login") {
  $login[pass] = $logins;
  session_register("login"); //���� ����Ѵ�. 
  header("Location: admin.php");
} else if ($mode == "logout") {
  session_destroy("login"); // ������ �����Ѵ�
  header("Location: auth.php");
} else if ($mode == "back") {
  header("Location:admin.php");
}

?>
