<?
/************************************************************
���� ��� �޼����� �����մϴ�. �ٸ� �� �߰� �ϰ� ������,
��� �ڵ�(���� ��� �ѱ��� ko)�� ������ �Ͽ� else if�� �ش�
�޼����� �ִ� ���ϵ��� "$locate/�����̸�" ���� �߰��� �ֽø� �˴ϴ�.
************************************************************/ 

if ($path[type] == "user_admin") $locate = "../../include/LANG";
else if ($path[type] == "admin" || $path[type] == "Install") $locate = "../include/LANG";
else $locate = "include/LANG";

if ($langs[code] == "ko") require("$locate/ko.ph");
else require("$locate/en.ph");
?>
