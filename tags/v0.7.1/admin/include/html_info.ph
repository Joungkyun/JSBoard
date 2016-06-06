<?

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.3                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/


$GLOBALS[admin_ownfile] ;
$GLOBALS[user_admin_ownfile] ;


if ( $admin_ownfile == "auth.php3" ) {

  $title = "Admin Login";

  $bg_color	= "black" ;
  $guide_color	= "white" ;

  $td_width1 = 10 ;
  $td_width2 = 1 ;
  $td_width3 = 49 ;
  $td_width4 = 430 ;

}
else if ( $admin_ownfile == "admin.php3" ) {

  $title = "Administration Center";

  $bg_color	= "black" ;
  $guide_color	= "white" ;

  $td_width1 = 10 ;
  $td_width2 = 1 ;
  $td_width3 = 49 ;
  $td_width4 = 430 ;

  $scale     = 10 ;

}
else if ( $user_admin_ownfile == "uadmin.php3" ) {

  $title = "User Configuration";

  $bg_color	= "black" ;
  $guide_color	= "white" ;

  $td_width1 = 10 ;
  $td_width2 = 1 ;
  $td_width3 = 49 ;
  $td_width4 = 430 ;

}


?>