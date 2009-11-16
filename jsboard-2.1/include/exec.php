<?php
# $Id: exec.php,v 1.4 2009-11-16 21:52:47 oops Exp $

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

# return 1 => path is none
#        2 => path is not directory
#        3 => create failed
#        0 => success
function mkdir_p ($path, $mode) {
  if ( ! trim ($path) ) return 1;
  $_path = explode ('/', $path);
  $_no = count ($_path);

  for ( $i=0; $i<$_no; $i++ ) {
    $mknewpath .= "{$_path[$i]}/";
    $mkpath = preg_replace ("!/$!", "", $mknewpath);

    if ( is_dir ($mkpath) || ! trim ($mkpath)) {
      continue;
    } elseif ( file_exists ($mkpath) ) {
      $ret = 2;
      break;
    } else {
      $ret = mkdir ($mkpath, $mode);
      if ( $ret == FALSE ) {
        $ret = 3;
        break;
      }
    }
  }
  return 0;
}
?>
