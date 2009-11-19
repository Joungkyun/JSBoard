<?php
######################################################################
# 다중 언어 메세지를 설정합니다. 다른 언어를 추가 하고 싶으면,
# 언어 코드(예를 들어 한글은 ko)를 변수로 하여 else if로 해당
# 메세지가 있는 파일들을 "$locate/파일이름" 으로 추가해 주시면 됩니다.
# $Id: lang.ph,v 1.6 2009-11-19 19:10:58 oops Exp $
######################################################################

switch($path['type']) {
  case 'user_admin' :
    $locate = '../../include/LANG';
    break;
  case 'admin' :
  case 'Install' :
    $locate = '../include/LANG';
    break;
  default :
    $locate = 'include/LANG';
}

if ($langs['code'] == "ko") { require "{$locate}/ko.ph"; }
else { require "{$locate}/en.ph"; }
?>
