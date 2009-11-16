<?
# $Id: lang.php,v 1.2 2009-11-16 21:52:46 oops Exp $

if ( ! isset ($_supportlang) ) {
  $d = opendir ($dpath . '/language');
  while ( $p = readdir ($d) ) {
    if ( preg_match ('/^[a-z]{2}\.lang$/', $p) ) {
      $_supportlang[] = preg_replace ('/\.lang$/', '', $p);
      require_once ($p);
    }
  }
  closedir ($d);
}

$_ = '_lang';

function _lang ($_m, $_code = '') {
  $_code = ! $_code ? getenv ('JSLANG') : $_code;

  switch ($_code) {
    case 'ko' :
      return $GLOBALS['ko'][$_m];
      break;
    case 'en' :
      return $GLOBALS['en'][$_m];
      break;
    default :
      if ( ! $GLOBALS[$_code][$_m] )
         return $GLOBALS['en'][$_m];

      return $GLOBALS[$_code][$_m];
  }
}
?>
