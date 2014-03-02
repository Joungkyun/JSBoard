<?php
# $Id: make_key.php,v 1.5 2014-03-02 17:11:30 oops Exp $
# alphabat uper case range 65 ~ 90

$fname = 'captcha-' . getmypid () . '.db';

$ascii = array ('35-38', 47, '50-57', '63-90');
foreach ( $ascii as $v ) {
  if ( is_numeric ($v) )
    $list[] = chr ($v);
  else {
    $_v = explode ('-', $v);
    for ( $i=$_v[0]; $i<=$_v[1]; $i++ )
      $list[] = chr ($i);
  }
}

$start = 0;
$end   = count ($list) - 1;

for ( $i=0; $i<1000; $i++ ) {
  unset ($tmp);
  for ( $j=0; $j<5; $j++ ) {
    $v = rand ($start, $end);
    $tmp[$j] = $list[$v];
  }
  $vari[$i] = implode ('', $tmp);
}

$p = serialize ($vari);

file_put_contents ($fname, $p);

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */
?>
