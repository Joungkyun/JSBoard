<!-- ========================= Tail ========================= -->
<?
if(session_is_registered("$jsboard") || $HTTP_COOKIE_VARS[$cjsboard][id]) {
  echo "<A HREF=user.php?table=$table>$langs[u_print]</A><BR>";
}
?> 
<!-- ========================= Tail ========================= -->
