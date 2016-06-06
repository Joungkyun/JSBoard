<?php
/*
 * This file is a part of Secure URI pear packages.
 * $Id: sURI.php,v 1.4 2009-11-19 05:29:51 oops Exp $
 *
 * sURI class
 *
 * PHP version 5
 *
 * LICENSE: BSD license
 *
 * @category   pear
 * @package    sURI
 * @author     JoungKyun.Kim <http://oops.org>
 * @copyright  1997-2008 OOPS.ORG
 * @license    BSD License
 * @version    CVS: $origId: sURI.php,v 1.2 2009/02/26 05:30:35 oops Exp $
 * @since      File available since Release 0.1
 */

function block_devided ($string, $block = null) {
  if ( ! is_array ($block) )
    return false;

  while ( ($p = array_pos ($string, $block)) !== false ) {
    if ( $p->pos > 0 ) {
      $_r = substr ($string, 0, $p->pos);
      if ( preg_match ("/\n([: ]*:)$/", rtrim ($_r), $matches) ) {
        $r[] = preg_replace ("/\n[: ]+$/", '', $_r);
      } else {
        $r[]= $_r;
      }
      $string = substr ($string, $p->pos);
    }

    $end = preg_replace ('/[\[<]/', '\\0/', $p->needle);
    if ( ($pp = strpos ($string, $end)) !== false ) {
      $r[] = substr ($string, 0, $pp + strlen ($end));
      $string = substr ($string, $pp + strlen ($end) + 1);
    } else
      break;
  }

  if ( $string )
    $r[] = $string;

  return $r;
}

function array_pos ($haystack, $needle = null) {
  if ( ! is_array ($needle) )
    return false;

  $p = null;
  $r = (object) array ('needle' => false, 'pos' => false);

  foreach ( $needle as $val )
    $p[] = strpos ($haystack, $val);

  if ( ! is_array ($p) )
    return false;

  $mlen = false;
  $chkkey = 0;

  foreach ( $p as $key => $v ) {
    if ( $v === false )
      continue;

    if ( $mlen !== false ) {
      $mlen = ($mlen > $v) ? $v : $mlen;
      $chkkey = $key;
    } else
      $mlen = $v;
  }

  if ( $mlen === false )
    return false;

  $r->needle = $needle[$chkkey];
  $r->pos = $mlen;

  return $r;
}
?>
