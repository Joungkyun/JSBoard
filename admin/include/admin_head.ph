<?
# session�� ����
session_start();
if(!session_is_registered("login")) session_destroy();

include_once '../include/variable.ph';
include_once "../include/print.ph";
# register_globals �ɼ��� ������ ���� �ʱ� ���� �Լ�
if(!$parse_query_str_check) parse_query_str();

# Copyright ����
$copy[name]     = "JSBoard Open Project";
$copy[email]    = "";
$copy[url]      = "http://jsboard.kldp.net/";
$copy[version]  = "3.0";

include_once "./include/config.ph";
include_once "../config/global.ph";

$path[type] = "admin";

include_once "../include/error.ph";
include_once "../include/lang.ph";
include_once "../include/exec.ph";
include_once "../include/get.ph";
include_once "./include/print.ph";
include_once "./include/check.ph";
include_once "./include/get.ph";
include_once "./include/first_reg.ph";

# �⺻ ������ ���� �׸� �б�
if(file_exists("../config/default.themes") && $color[theme])
  { include_once "../config/default.themes"; }

$agent = get_agent();
if(preg_match("/links|w3m|lynx/i",$agent[br])) $textBrowser = 1;
?>
