<?php
######################################################################
# ���� ��� �޼����� �����մϴ�. �ٸ� �� �߰� �ϰ� ������,
# ��� �ڵ�(���� ��� �ѱ��� ko)�� ������ �Ͽ� else if�� �ش�
# �޼����� �ִ� ���ϵ��� "$locate/�����̸�" ���� �߰��� �ֽø� �˴ϴ�.
# $Id: lang.php,v 1.2 2009-11-19 05:29:51 oops Exp $
######################################################################

if ($path['type'] == "user_admin") $locate = "../../include/LANG";
else if ($path['type'] == "admin" || $path['type'] == "Install") $locate = "../include/LANG";
else $locate = "include/LANG";

if ($langs['code']) {
  if (file_exists("$locate/{$langs['code']}.php")) {
    include "$locate/{$langs['code']}.php";
  } else { include "$locate/en.php"; }
} else { include "$locate/en.php"; }
?>
