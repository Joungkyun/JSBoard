<?php
# $Id: exec.php,v 1.3 2014-03-02 17:11:31 oops Exp $

# // {{{ +-- public unlink_r ($dir)
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
// }}}

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
