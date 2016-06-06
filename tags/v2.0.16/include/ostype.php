<?php
# $Id: ostype.php,v 1.3 2014/03/02 17:11:31 oops Exp $
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

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim600: filetype=php et ts=2 sw=2 fdm=marker
 * vim<600: filetype=php et ts=2 sw=2
 */
?>
