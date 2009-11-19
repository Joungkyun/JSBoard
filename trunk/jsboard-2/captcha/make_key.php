<?
# $Id: make_key.php,v 1.2 2009-11-19 05:29:50 oops Exp $
# alphabat uper case range 65 ~ 90

$fname = 'captcha-' . getmypid () . '.db';

$ascii = array ('35-38', 47, '50-57', '63-90');
foreach ( $ascii as $v ) :
	if ( is_int ($v) ) :
		$list[] = chr ($v);
	else :
		$_v = explode ('-', $v);
		for ( $i=$_v[0]; $i<=$_v[1]; $i++ )
			$list[] = chr ($i);
	endif;
endforeach;

$start = 0;
$end   = count ($list) - 1;

for ( $i=0; $i<1000; $i++ ) :
	unset ($tmp);
	for ( $j=0; $j<5; $j++ ) :
		$v = rand ($start, $end);
		$tmp[$j] = $list[$v];
	endfor;
	$vari[$i] = implode ('', $tmp);
endfor;

$p = serialize ($vari);

file_put_contents ($fname, $p);
?>
