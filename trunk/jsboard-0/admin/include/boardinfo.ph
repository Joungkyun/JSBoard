<?

function board_info($super_user) {

  global $db_name, $db_server, $db_user, $db_pass ;

  $connect=mysql_connect( $db_server, $db_user, $db_pass) or  
                     die( "  Do not access SQL server"); 

  mysql_select_db("$db_name",$connect);

  $super_query="select super_user from BoardInformation where t_name='superuser'" ;
  $super_result = mysql_query($super_query,$connect );
  $super=mysql_fetch_array($super_result);

  mysql_close() ;

  $super_user = $super[super_user] ;
  return $super_user ;

}

function superpass_info($lang) {

  global $db_name, $db_server, $db_user, $db_pass ;

  $connect=mysql_connect( $db_server, $db_user, $db_pass) or  
                     die( "  Do not access SQL server"); 

  mysql_select_db("$db_name",$connect);

  $super_query="select lang from BoardInformation where t_name='superuser'" ;
  $super_result = mysql_query($super_query,$connect );
  $super=mysql_fetch_array($super_result);

  mysql_close() ;

  $lang = $super[lang] ;
  return $lang ;

}

function config_info() {

  global $db_name, $db_server, $db_user, $db_pass, $table ;

  $user_connect=mysql_connect( $db_server, $db_user, $db_pass) or  
                     die( "  Do not access SQL server"); 
  mysql_select_db("$db_name",$user_connect);

  $user_query="select * from BoardInformation where t_name='$table'" ;
  $user_result = mysql_query($user_query,$user_connect );
  $user=mysql_fetch_array($user_result);

  mysql_close() ;

  return $user;

}

?>