<table width="<? echo $width ?>" border="0" cellpadding="4" align="center">
<tr>
  <td>
    <font size="2" color="<? echo $l0_fg ?>">
    <form method="post" action="list.php3?table=<? echo $table ?>">
    <input type="hidden" name="act" value="search">
    <img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
    <select name="sc_column">
    <option value="title" selected>제목
    <option value="text">내용
    <option value="name">글쓴이
    <option value="all">모두
    </select>
    <input type="text" name="sc_string" size="<? sform(10) ?>" maxlength="50">
    <input type="submit" value="검색">
    <br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
    </font>
  </td><td valign="top" align="right">
    <font size="2">
    <? nlist($page) ?>
    </font>
  </td>
</tr></table>
