<?
if(file_exists("data/$table/html_tail.ph"))
  { include("data/$table/html_tail.ph"); }
else if(file_exists("html/tail2.ph")) 
  { include("html/tail2.ph"); }
?>

</BODY>
</HTML>
