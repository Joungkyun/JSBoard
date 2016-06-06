<!-- =============================== Tail =============================== -->
<?
if ( session_is_registered ($jsboard) || $_COOKIE[$cjsboard]['id'] ) {
  echo "<a href=\"{$board['path']}user.php?table={$table}\">" . $_('u_print') . "</a><br>";
}
?> 
<!-- =============================== Tail =============================== -->
