<?php
# $Id: variable.php,v 1.4 2009-11-19 03:22:09 oops Exp $

$jsvari = (object) array (
  'atc'     => (object) array (
    'ckeyv' => '/[^[:alnum:]#$%&\/?@]/',
    'ckey' => '/[^0-9]/',
    'reno' => '/[^0-9]/',
    'html' => '/[^0-9]/',
    'no'   => '/[^0-9]/',
    'delete_dir' => '/[^a-z0-9\/_-]/i',
    'delete_filename' => '/[^a-z0-9_-.]/i',
  ),
  'cenable' => (object) array (
    'ore' => '/[^0-9]/',
  ),
  'check'   => '/[^0-9]/',
  'goaway'  => '/[^0-9]/',
  'lu'      => '/[^\xA1-\xFEa-z0-9.]/',
  'm'       => '/[^[:alpha:]]/',
  'nd'      => '/[^0-9]/',
  'no'      => '/[^0-9]/',
  'o'       => (object) array (
    'at'  => '/[^a-z_]/i',
    'er'  => '/[^[:alpha:]]/',
    'sc'  => '/[^[:alpha:]]/',
    'sct' => '/[^[:alpha:]]/',
    'stt' => '/[^[:alpha:]]/',
    'st'  => '/[^[:alpha:]]/',
    'y1'  => '/[^0-9]/',
    'y2'  => '/[^0-9]/',
    'm1'  => '/[^0-9]/',
    'm2'  => '/[^0-9]/',
    'd1'  => '/[^0-9]/',
    'd2'  => '/[^0-9]/'
  ),
  'page'    => '/[^0-9]/',
  't'       => '/[^[:alpha:]]/',
  'table'   => '/[^A-Za-z0-9_-]/',
  'type'    => '/[^[:alpha:]]/',
);

function fatal_error ($msg) {
  echo <<<EOF
<script type="text/javascript">
  alert('$msg');
</script>
EOF;
  exit;
}

function confirm_variable ($key, $value) {
  global $jsvari;
  if (!isset($jsvari->$key))
    return;

  if (is_array($value)) {
    foreach ($value as $k => $v) {
      if (!isset($jsvari->$key->$k)) continue;
      if (preg_match($jsvari->$key->$k,$v)) {
        $msg = sprintf('You can not access with %s[%s]=%s', $key, $k, $v);
        fatal_error($msg);
        exit;
      }
    }
	return;
  }

  if (preg_match($jsvari->$key,$value)) {
    $msg = sprintf('You can\\\'t access with %s=%s', $key, $value);
    fatal_error($msg);
    exit;
  }
  return;
}
?>
