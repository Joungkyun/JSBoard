<?
# OS 마다 틀리게 적용되는 변수값을 지정을 한다.
# get OS type for password field in MySQL and
# sample files
if(eregi("linux",$OSTYPE)) {
  $ostypes[pfield] = "13";
  $ostypes[name] = "Linux";
  $ostypes[dpass] = "lHJTjGW8VhHc.";
} elseif (eregi("freebsd",$OSTYPE)) {
  $ostypes[pfield] = "56";
  $ostypes[name] = "FreeBSD";
  $ostypes[dpass] = "\$1\$Cx\$.2OyfWZCiPTc4sSw0vswc/";
} else {
  $ostypes[pfield] = "13";
  $ostypes[name] = "Others";
  $ostypes[dpass] = "lHJTjGW8VhHc.";
}
?>
