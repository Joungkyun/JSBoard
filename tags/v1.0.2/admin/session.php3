<?
session_start(); // session�� �����Ѵ�.
if ($mode == "login") {
  session_register("login"); //���� ����Ѵ�. 
  header("Location: admin.php3");
} else if ($mode == "logout") {
  session_destroy("login"); // ������ �����Ѵ�
  header("Location: auth.php3");
} else if ($mode == "back") {
  header("Location:admin.php3");
}

?>
