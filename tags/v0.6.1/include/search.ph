<table width="<? echo $width ?>" border="0" cellpadding="4" align="center">
<tr>
  <td>
    <font size="2" color="<? echo $l0_fg ?>">
    <form method="post" action="list.php3?table=<? echo $table ?>">
    <input type="hidden" name="act" value="search">
    <img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
    <select name="sc_column">
    <option value="title" selected>����
    <option value="text">����
    <option value="name">�۾���
    <option value="all">���
    </select>
    <input type="text" name="sc_string" size="<? sform(10) ?>" maxlength="50">
    <input type="submit" value="�˻�">
    <br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
    </font>
  </td><td valign="top" align="right">
    <font size="2">
    <? nlist($page) ?>
    </font>
  </td>
</tr></table>
