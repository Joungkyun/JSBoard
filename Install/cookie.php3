<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.kr.net http://oops.kr.net                    *
*                                                                       *
************************************************************************/


if ($mode == "login") {

  if (!$timeout) { $timeout = "time()+900" ; }

  $Cookie_expire = $timeout ; 

  $mysql_pass = crypt("$mysql_pass","oo") ;

  SetCookie("mysql_root","$mysql_pass","$Cookie_expire"); 
  header("Location: mysql_user_regist.php3") ;
}

else if ($mode == "logout") {

  SetCookie("mysql_root","","0"); 
  header("Location: index.php3") ;
}


else if ($mode == "first") {

  SetCookie("mysql_root","","0"); 

  echo("<script>\n" .
       "  alert('등록이 완료 되었습니다.\\nAdmin Page로 이동을 합니다.\\nAdmin Page의 초기 Password는\\n0000 입니다.')\n" .
       "  document.location='../admin/index.php3'\n" .
       "</script> ") ;
  exit ;

}

?>
