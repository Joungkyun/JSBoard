<?
if ( ! isset ($_supportlang) ) {
  $_dp = isset ($prlist['path']) ? $prlist['path'] : '.';
  $d = opendir ("{$_dp}/language");
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
    case 'ja' :
      return $GLOBALS['ja'][$_m];
    default :
      if ( ! $GLOBALS[$_code][$_m] )
         return $GLOBALS['en'][$_m];

      return $GLOBALS[$_code][$_m];
  }
}
?>
