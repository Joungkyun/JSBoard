<?php

$version = "OOPS administration center v.1.3";

echo ("
<!----
/************************************************************************
*                                                                       *
*                  OOPS Administration Center v1.3                      *
*                      Scripted by JoungKyun Kim                        *
*                admin@oops.org http://www.oops.org                     *
*                                                                       *
************************************************************************/
//--->

<html>
<head>
");

$user_admin_ownfile = explode("/user_admin/",$PHP_SELF) ;
$user_admin_ownfile = $user_admin_ownfile[1] ;

$admin_ownfile = explode("/admin/",$PHP_SELF) ;
$admin_ownfile = $admin_ownfile[1] ;


if ( $admin_ownfile == "auth.php3" || $admin_ownfile == "admin.php3" ) {
  include("./include/html_info.ph");
}
else if ( $user_admin_ownfile == "uadmin.php3" ) {
  include("../include/html_info.ph");
}


if ( $admin_ownfile == "auth.php3" ) { 
  echo ("<title>$version [ Whole ADMIN page ]</title>");
}
else if ( $admin_ownfile == "admin.php3" ) { 
  echo ("<title>$version [ Whole ADMIN page ]</title>
         <script language='JavaScript'>

         <!--
             var child = null;
             var count = 0;

             function fork ( type , url ) {
               var childname = 'BoardManager' + count++;

               if(child != null) {    // child was created before.
                 if(!child.closed) {  // if child window is still opened, close window.
                   child.close();
                 }
               }
               // here, we can ensure that child window is closes.
                    if(type == 'popup' ) child = window.open(url, childname, 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=700,height=600');
               else if(type == 'popup1' ) child = window.open(url, childname, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=300');
               else                       alert('Fatal : in function fork()');
               return;
             }

             function logout () {
               document.location='./cookie.php3?mode=logout' ;
             }

         // -->
         </script>
       ") ; 
}
else if ( $user_admin_ownfile == "uadmin.php3" ) {
  echo ("<title>$version [ User ADMIN page ]</title>

<script language='JavaScript'>


<!--
    var child = null;
    var count = 0;

    function fork ( type , url ) {
      var childname = 'BoardManager' + count++;

      if(child != null) {
        if(!child.closed) {
          child.close();
        }
      }
      // here, we can ensure that child window is closes.
           if(type == 'hint' ) child = window.open(url, childname, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=250,height=150');
      else                       alert('Fatal : in function fork()');
      return;
    }
// -->

</script>
       ") ; 
}

echo ("

<style type='text/css'>
a:link { text-decoration:none; color:white ; }
a:visited { text-decoration:none; color:white ; }
a:hover { color:red; }
td { font-size:9pt; color:#999999 }
 #title {font-size:20pt; color:#555555}
 #td {font-size:9pt; color:#999999}
 #ac {font-size:9pt; color:white}
 #input {font: 9pt ±¼¸²; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
 #submit {font: 9pt ±¼¸²; BACKGROUND-COLOR:black; COLOR:#999999; BORDER:2x solid #555555}
</style>

</head>

<body bgcolor=$bg_color>

<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
<tr><td>


<table border=0 width=$table_width cellspacing=0 cellpadding=0 align=center>

<tr>
<td width=$td_width1><img src=./img/blank.gif width=$td_width1 height=10></td>
<td width=$td_width2 bgcolor=$guide_color><img src=./img/blank.gif width=$td_width2 height=10></td>
<td width=$td_width3><img src=./img/blank.gif width=$td_width3 height=1></td>
<td width=$td_width4><img src=./img/blank.gif width=$td_width4 height=1></td>
<td width=$td_width3><img src=./img/blank.gif width=$td_width3 height=1></td>
<td width=$td_width2><img src=./img/blank.gif width=$td_width2 height=1></td>
<td width=$td_width1><img src=./img/blank.gif width=$td_width1 height=1></td>
</tr>

<tr>
<td colspan=4 bgcolor=$guide_color><img src=./img/blank.gif width=1 height=1></td>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
</tr>

<tr>
<td rowspan=2><img src=./img/blank.gif width=1 height=1></td>
<td rowspan=2 bgcolor=$guide_color><img src=./img/blank.gif width=1 height=1></td>
<td colspan=3><img src=./img/blank.gif width=1 height=1></td>
<td colspan=2><img src=./img/blank.gif width=1 height=$td_width3></td>
</tr>


<tr>
<td colspan=3 align=center>

<font id=title><b>");

if ( $admin_ownfile == "admin.php3") {

  echo ("<table border=0 cellpadding=0 cellspacing=0 border=0>\n" .
        "<tr>\n" .
        "<td rowspan=2 valign=buttom><font id=title><b>OOPS&nbsp;</b></font></td>\n" .
        "<td valign=bottom> for JS Board</td>\n" .
        "</tr>\n" .
        "\n" .
        "<tr>\n" .
        "<td valign=top>Administration Center v1.3</td>\n" .
        "</tr>\n" .
        "</table>") ;

}

else { echo "$title" ; }

echo ("</b></font>

<p>

<!--------------------------- Upper is HTML_HEAD --------------------------->


");

?>
