<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/

if ($mode == "login") {

  if (!$timeout) { $timeout = "time()+900" ; }

  $Cookie_expire = $timeout ; 

  SetCookie("login_pass","$login_pass","$Cookie_expire"); 
  header("Location: admin.php3") ;
}

else if ($mode == "logout") {

  SetCookie("login_pass","","0"); 
  header("Location: auth.php3") ;
}

else if ($mode == "back") {

  header("Location:admin.php3");
}

?>