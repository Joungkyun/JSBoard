<?php
if (file_exists('../config/global.php')) {
  Header ('Content-Type: text/plain; charset=utf-8');
  printf ('Already installed!!!');
  exit;
}

header("location:auth.php");
?>
