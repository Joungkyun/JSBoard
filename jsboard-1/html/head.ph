<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo $langs[charset] ?>">
<TITLE>Jsboard <? echo $version ?> - <? echo get_title() ?></TITLE>
<STYLE TYPE="text/css">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 9pt <? echo $langs[font] ?>; }
INPUT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[n2_bg] ?>; COLOR:<? echo $color[n2_fg] ?>; BORDER:<? echo $form_border ?> solid <? echo $color[l1_bg] ?>}
SELECT {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[n2_bg] ?>; COLOR:<? echo $color[n2_fg] ?>; BORDER:1x solid <? echo $color[l1_bg] ?>}
TEXTAREA {font: 10pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[n2_bg] ?>; COLOR:<? echo $color[n2_fg] ?>; BORDER:<? echo $form_border ?> solid <? echo $color[l1_bg] ?>}
 #radio {font: 9pt <? echo $langs[font] ?>; BACKGROUND-COLOR:<? echo $color[bgcol] ?>; COLOR:<? echo $color[text] ?>; BORDER:2x solid <? echo $color[bgcol] ?>}
 #title {font:20pt <? echo $langs[font] ?>; color:<? echo $color[n0_bg] ?>}
 #eng {font:11px tahoma,Verdana,arial; line-height:120%}
-->
</STYLE>
<?
if($view[email] == "yes" || ($upload[yesno] == "yes" && $cupload[yesno] == "yes")) {
echo "
<script LANGUAGE=JavaScript>
<!-- Begin
  var child = null;
  var count = 0;
  function new_windows(addr,tag,scroll,resize,wid,hei) {
    if (self.screen) {
      width = screen.width
      height = screen.height
    } else if (self.java) {
      var def = java.awt.Toolkit.getDefaultToolkit();
      var scrsize = def.getScreenSize();
      width = scrsize.width;
      height = scrsize.height;
    }

    if (width < wid) { wid = width - 5
      hei = height - 60
      scroll = 'yes'
    }

    var childname = 'JSBoard' + count++;
    // child window가 떠 있을 경우 child window를 닫는다.
    if(child != null) {
      if(!child.closed) { child.close(); }
    }
    child = window.open(addr,tag,'left=0, top=0, toolbar=0,scrollbars=' + scroll + ',status=0,menubar=0,resizable=' + resize + ',width=' + wid + ',height=' + hei +'');
    // child window가 로드 될때 가장 앞으로 올린다.
    child.focus();
    return;
  }
//-->
</script>\n";
} 
?>
</HEAD>

<?
echo "<BODY BACKGROUND=\"$color[image]\" BGCOLOR=\"$color[bgcol]\" TEXT=\"$color[text]\" LINK=\"$color[link]\" VLINK=\"$color[vlink]\" ALINK=\"$color[alink]\">\n";

if (eregi("[^a-z0-9_\-]",$table))
  print_error("Can't use special characters except alphabat, numberlic , _, - charcters");

if(file_exists("data/$table/html_head.ph")) {
  @include "data/$table/html_head.ph";
} else if(file_exists("html/head2.ph")) {
  @include "html/head2.ph";
}
?>


