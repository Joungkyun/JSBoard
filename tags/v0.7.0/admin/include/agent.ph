<? 
/* 
** Cloaking Variables 
** Version 2.0 
** Leon Atkisnon <leon@clearink.com> 
** with contributions from Chris Mospaw <mospaw@polk-county.com> 
** 
** This bit of code parses HTTP_USER_AGENT and sets the following variables: 
** Browser_Name 
** Browser_Version 
** Browser_Platform 
** Browser_JavaScriptOK 
** Browser_CSSOK 
** Browser_TextOnly 
** Browser_FileUpload 
** 
** JavaScriptOK means that the browser understands JavaScript on 
** the same level the Navigator 3 does. Specifically, it can use 
** named images. This allows easier rollovers. If a browser doesn't 
** do this (Nav 2 or MSIE 3), then we just assume it can't do any 
** JavaScript. Referencing images by load order is too hard to maintain. 
** 
** CSSOK is kind of sketchy in that Nav 4 and MSIE work differently, 
** but they do seem to have most of the functionality. MSIE 4 for the 
** Mac has buggy CSS support, so we let it do JavaScript, but no CSS. 
*/ 

// Get the name the browser calls itself and what version 

$Browser_Name = strtok($HTTP_USER_AGENT, "/"); 
$Browser_Version = strtok( " "); 

// MSIE lies about its name 

if(ereg( "MSIE", $HTTP_USER_AGENT)) { 
  $Browser_Name = "MSIE"; 
  $Browser_Version = strtok( "MSIE"); 
  $Browser_Version = strtok( " "); 
  $Browser_Version = strtok( ";"); 
} 

// Opera isn't completely honest, either ... 
// Modificaton by Chris Mospaw <mospaw@polk-county.com> 

if(ereg( "Opera", $HTTP_USER_AGENT)) { 
  $Browser_Name = "Opera"; 
  $Browser_Version = strtok( "Opera"); 
  $Browser_Version = strtok( "/"); 
  $Browser_Version = strtok( ";"); 
} 


// try to figure out what platform, windows or mac 

$Browser_Platform = "unknown"; 

if(ereg( "Windows",$HTTP_USER_AGENT) || ereg( "WinNT",$HTTP_USER_AGENT) || ereg( "Win98",$HTTP_USER_AGENT) || ereg( "Win95",$HTTP_USER_AGENT)) { 
  $Browser_Platform = "Windows"; 
} 

if(ereg( "Mac", $HTTP_USER_AGENT)) { 
  $Browser_Platform = "Macintosh"; 
} 

//default to no JavaScript or CSS support 

/*
$Browser_JavaScriptOK = FALSE; 
$Browser_CSSOK = FALSE; 
$Browser_FileUpload = FALSE; 

if($Browser_Platform == "Windows") { 

  if($Browser_Name == "Mozilla") { 

    if($Browser_Version >= 3.0) { 
      $Browser_JavaScriptOK = TRUE; 
      $Browser_FileUpload = TRUE; 
      } 

    if($Browser_Version >= 4.0) { 
      $Browser_CSSOK = TRUE; 
      } 
    } 

  elseif($Browser_Name == "MSIE") { 

    if($Browser_Version >= 4.0) { 
      $Browser_JavaScriptOK = TRUE; 
      $Browser_FileUpload = TRUE; 
      $Browser_CSSOK = TRUE; 
    } 
  } 

  elseif($Browser_Name == "Opera") { 

    if($Browser_Version >= 3.0) { 
      $Browser_JavaScriptOK = TRUE; 
      $Browser_FileUpload = TRUE; 
      $Browser_CSSOK = TRUE; 
    } 
  } 
} 

elseif($Browser_Platform == "Macintosh") { 

  if($Browser_Name == "Mozilla") { 

    if($Browser_Version >= 3.0) { 
      $Browser_JavaScriptOK = TRUE; 
      $Browser_FileUpload = TRUE; 
      } 
    if($Browser_Version >= 4.0) { 
      $Browser_CSSOK = TRUE; 
      } 
  } 
  elseif($Browser_Name == "MSIE") { 

    if($Browser_Version >= 4.0)   { 
      $Browser_JavaScriptOK = TRUE; 
      $Browser_CSSOK = TRUE; 
      $Browser_FileUpload = TRUE; 
    } 
  } 
} 

*/


function netscape_browser() {

  global $Browser_Name, $super_user ;

  if ($Browser_Name == "Mozilla" && $super_user == "ooK/oSLfDJOUI") { 

    echo ("<script>\n" .
          "var message = 'Windog Netscape ����ڵ��� Variable Width Font��\\nFixed Width Font�� ���� size 11, ����ü size 11 ��\\n�����ϼ����� ���� �������� ���Ǽ� �ֽ��ϴ�.\\n\\n�� �޼����� Netscape User�� admin password��\\n�ʱⰪ �϶��� �������ϴ�.\\n\\n�� �޼����� ������ �������� admin�� password��\\n�����Ͻʽÿ�. Password�� Admin info button����\\n�����ϽǼ� �ֽ��ϴ�.'\n" .
          "alert(message)\n" .
          "</script>") ;

  }

}

?> 