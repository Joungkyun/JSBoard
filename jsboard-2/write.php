<?
include "include/header.ph";
echo $_SERVER["HTTP_USER_AGENT"];

echo "<xmp>
$agent[br]
$agent[vr]
$agent[os]
$agent[co]
$agent[ln]
</xmp>";

$board[headpath] = @file_exists("data/$table/html_head.ph") ? "data/$table/html_head.ph" : "html/nofile.ph";
$board[tailpath] = @file_exists("data/$table/html_tail.ph") ? "data/$table/html_tail.ph" : "html/nofile.ph";

$board[super] = $board[adm] ? 1 : $board[super];

if(eregi("^(1|3|4|5)$",$board[mode])) { if($board[super] != 1) print_error($langs[perm_err],250,150,1); }
if(!eregi("^(0|6)",$board[mode]) && !$_SESSION[$jsboard][id]) print_error($langs[perm_err],250,150,1);

if($board[mode] && $_SESSION[$jsboard][id]) {
  if(!eregi("^(1|4)$",$board[mode]) && $_SESSION[$jsboard][pos] != 1) $disable = " disabled";
  else $nodisable = 1;
} else $nodisable = 1;

if((eregi("^(2|3|5|7)$",$board[mode]) && $_SESSION[$jsboard][id]) || $board[super]) {
  $pre_regist[name] = $_SESSION[$jsboard][id];
  $pre_regist[rname] = $_SESSION[$jsboard][name];
  $pre_regist[email] = $_SESSION[$jsboard][email];
  $pre_regist[url] = $_SESSION[$jsboard][url];
} else {
  $pre_regist[name] = eregi_replace("[\]","",$_COOKIE[board_cookie][name]);
  $pre_regist[email] = eregi_replace("[\]","",$_COOKIE[board_cookie][email]);
  $pre_regist[url] = eregi_replace("[\]","",$_COOKIE[board_cookie][url]);
}

# ���� ������ �����ڿ��Ը� �־��� ��� �н����� üũ
$kind = "write";
if($board[notice]) print_notice($board[notice]);

# Browser�� Lynx�϶� multim form ����
if($agent[br] == "LYNX") $board[formtype] = "";
else $board[formtype] = " ENCTYPE=\"multipart/form-data\"";

# TEXTAREA�� wrap option check
$wrap = form_wrap();

# Form size ������ ���� ����
if ($agent[br] == "MSIE" || $agent[br] == "MOZL" || ($agent[br] == "NS" && $agent[vr] == 6)) {
  $orig_option = " onClick=fresize(0)";
  $print[operate] = form_operate("writep","wpost",$size[text]);
} else $print[operate] = "No support this browser";

$wkey = get_spam_value($board[antispam]);
$print[passform] = "<INPUT TYPE=hidden NAME=o[at] VALUE=write>\n".
                   "<INPUT TYPE=hidden NAME=table VALUE=$table>\n".
                   "<INPUT TYPE=hidden NAME=atc[wkey] VALUE=$wkey>\n";

$pre_regist[rname] = !$pre_regist[rname] ? "" : "\n<INPUT TYPE=hidden NAME=atc[rname] VALUE=\"$pre_regist[rname]\">";

if(!$nodisable) {
  $print[passform] .= "<INPUT TYPE=hidden NAME=atc[name] VALUE=\"$pre_regist[name]\">".
                      "$pre_regist[rname]".
                      "\n<INPUT TYPE=hidden NAME=atc[email] VALUE=\"$pre_regist[email]\">".
                      "\n<INPUT TYPE=hidden NAME=atc[url] VALUE=\"$pre_regist[url]\">\n";
} elseif($_SESSION[$jsboard][pos] == 1) {
  $print[passform] .= "$pre_regist[rname]\n";
}

$pages = $page ? "&page=$page" : "";

if($board[rnname] && eregi("^(2|3|5|7)",$board[mode]) && $_SESSION[$jsboard][pos] != 1) 
  $pre_regist[name] = $_SESSION[$jsboard][name] ? $_SESSION[$jsboard][name] : $pre_regist[name];

include "theme/$print[theme]/write.template";
?>
