<?php
# $Id: ostype.php,v 1.2 2009-11-16 21:52:47 oops Exp $
#
# OS 마다 틀리게 적용되는 변수값을 지정을 한다.
# get OS type for password field in MySQL and
# sample files
if(preg_match("/linux/i",$OSTYPE)) {
  $ostypes['name'] = "Linux";
  $ostypes['dpass'] = "lHJTjGW8VhHc.";
} elseif (preg_match("/freebsd/i",$OSTYPE)) {
  $ostypes['name'] = "FreeBSD";
  $ostypes['dpass'] = "\$1\$Cx\$.2OyfWZCiPTc4sSw0vswc/";
} else {
  $ostypes['name'] = "Others";
  $ostypes['dpass'] = "lHJTjGW8VhHc.";
}
?>
