<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $langs[charset] ?>">
<TITLE>Jsboard <? echo $version ?> - <? echo get_title() ?></TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 9pt <? echo $langs[font] ?>; }
INPUT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[l1_bg] ?>}
SELECT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:1x solid <? echo $color[l1_bg] ?>}
TEXTAREA {font: 10pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[l1_bg] ?>}
 #radio {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[bgcol] ?>}
 #title {font:20pt <? echo $langs[font] ?>; color:<? echo $color[n0_bg] ?>}
-->
</STYLE>
<?
if($view[email] == "yes") {
echo "
<script LANGUAGE=JavaScript>
<!-- Begin
  var child = null;
  var count = 0;
  function new_windows(addr,tag,scroll,resize,wid,hei) {
    var childname = 'JSBoard' + count++;
    // child window�� �� ���� ��� child window�� �ݴ´�.
    if(child != null) {
      if(!child.closed) { child.close(); }
    }
    child = window.open(addr,tag,'left=0, top=0, toolbar=0,scrollbars=' + scroll + ',status=0,menubar=0,resizable=' + resize + ',width=' + wid + ',height=' + hei +'');
    // child window�� �ε� �ɶ� ���� ������ �ø���.
    child.focus();
    return;
  }
//-->
</script>\n";
}
?>
</HEAD>

<?
echo ("<BODY BACKGROUND=\"$color[image]\" BGCOLOR=\"$color[bgcol]\" TEXT=\"$color[text]\" LINK=\"$color[link]\" VLINK=\"$color[vlink]\" ALINK=\"$color[alink]\">\n");

if(file_exists("data/$table/html_head.ph")) {
  @include("data/$table/html_head.ph");
} else if(file_exists("html/head2.ph")) {
  @include("html/head2.ph");
}
?>