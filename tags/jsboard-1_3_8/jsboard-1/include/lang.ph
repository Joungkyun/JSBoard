<?
######################################################################
# 다중 언어 메세지를 설정합니다. 다른 언어를 추가 하고 싶으면,
# 언어 코드(예를 들어 한글은 ko)를 변수로 하여 else if로 해당
# 메세지가 있는 파일들을 "$locate/파일이름" 으로 추가해 주시면 됩니다.
######################################################################

if ($path[type] == "user_admin") $locate = "../../include/LANG";
else if ($path[type] == "admin" || $path[type] == "Install") $locate = "../include/LANG";
else $locate = "include/LANG";

if ($langs[code] == "ko") { include "$locate/ko.ph"; }
else { include "$locate/en.ph"; }
?>
