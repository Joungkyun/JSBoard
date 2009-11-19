<?php
# $Id: exec.ph,v 1.4 2009-11-19 19:10:58 oops Exp $

# if failed, return 1
# else return 0
#
function unlink_r ($dir) {
  if ( preg_match ("!/\*$!", $dir) ) {
    $wildcard = 1;
    $dir = preg_replace ("!/\*$!", "", $dir);
  }

  if ( ! trim($dir) ) { return 0; }
  if ( ! file_exists ($dir) ) { return 0; }
  if ( file_exists ($dir) && ! is_dir ($dir) ) {
    @unlink ($dir);
    return 0;
  }

  $dh = opendir ($dir);

  while ( $file = readdir ($dh) ) {
    if( $file != "." && $file != ".." ) {
      $fullpath = $dir . "/" . $file;
      if ( !is_dir ($fullpath) ) {
        @unlink ($fullpath);
      } else {
        unlink_r ($fullpath);
      }
    }
  }

  closedir ($dh);

  if ( ! $wildcard ) {
    if( ! rmdir ($dir) ) return 1;
  } else {
    $err = filelist_lib ($dir);
    if ( count ($err) )
      return 1;
  }

  return 0;
}
?>
