<?
# OS ���� Ʋ���� ����Ǵ� �������� ������ �Ѵ�.
# get OS type for password field in MySQL and
# sample files
if(preg_match("/linux/i",$OSTYPE)) {
  $ostypes[name] = "Linux";
  $ostypes[dpass] = "lHJTjGW8VhHc.";
} elseif (preg_match("/freebsd/i",$OSTYPE)) {
  $ostypes[name] = "FreeBSD";
  $ostypes[dpass] = "\$1\$Cx\$.2OyfWZCiPTc4sSw0vswc/";
} else {
  $ostypes[name] = "Others";
  $ostypes[dpass] = "lHJTjGW8VhHc.";
}
?>
