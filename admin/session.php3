<?
session_start(); // session을 시작한다.
if ($mode == "login") {
  session_register("login"); //세션 등록한다. 
  header("Location: admin.php3");
} else if ($mode == "logout") {
  session_destroy("login"); // 세션을 삭제한다
  header("Location: auth.php3");
} else if ($mode == "back") {
  header("Location:admin.php3");
}

?>
