<?
# OS ���� Ʋ���� ����Ǵ� �������� ������ �Ѵ�.
# get OS type for password field in MySQL and
# sample files
if(eregi("linux",$OSTYPE)) {
  $ostypes[pfield] = "13";
  $ostypes[name] = "Linux";
} elseif (eregi("freebsd",$OSTYPE)) {
  $ostypes[pfield] = "56";
  $ostypes[name] = "FreeBSD";
} else {
  $ostypes[pfield] = "13";
  $ostypes[name] = "Others";
}

?>
