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
       "  alert('����� �Ϸ� �Ǿ����ϴ�.\\nAdmin Page�� �̵��� �մϴ�.\\nAdmin Page�� �ʱ� Password��\\n0000 �Դϴ�.')\n" .
       "  document.location='../admin/index.php3'\n" .
       "</script> ") ;
  exit ;

}

?>
