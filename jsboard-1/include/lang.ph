<?
######################################################################
# ���� ��� �޼����� �����մϴ�. �ٸ� �� �߰� �ϰ� ������,
# ��� �ڵ�(���� ��� �ѱ��� ko)�� ������ �Ͽ� else if�� �ش�
# �޼����� �ִ� ���ϵ��� "$locate/�����̸�" ���� �߰��� �ֽø� �˴ϴ�.
# $Id: lang.ph,v 1.5 2009-11-19 17:16:34 oops Exp $
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
