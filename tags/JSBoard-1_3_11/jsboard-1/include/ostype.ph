<?
# OS ���� Ʋ���� ����Ǵ� �������� ������ �Ѵ�.
# get OS type for password field in MySQL and
# sample files
if(preg_match("/linux/i",$OSTYPE)) {
  $ostypes[pfield] = "56";
  $ostypes[name] = "Linux";
} elseif (preg_match("/freebsd/i",$OSTYPE)) {
  $ostypes[pfield] = "56";
  $ostypes[name] = "FreeBSD";
} else {
  $ostypes[pfield] = "56";
  $ostypes[name] = "Others";
}
?>
