<?php
# $Id: variable.ph,v 1.2 2012-01-24 16:30:08 oops Exp $

$jsvari = (object) array (
  'atc'     => (object) array (
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
  'lu'      => '/[^\xA1-\xFEa-z0-9.]/',
  'm'       => '/[^[:alpha:]]/',
  'nd'      => '/[^0-9]/',
  'no'      => '/[^0-9]/',
  'o'       => (object) array (
    'at'  => '/[^[:alpha:]]/',
    'er'  => '/[^[:alpha:]]/',
    'sc'  => '/[^[:alpha:]]/',
    'sct' => '/[^[:alpha:]]/',
    'stt' => '/[^[:alpha:]]/',
    'st'  => '/[^[:alpha:]]/',
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

      if (!is_string ($jsvari->$key->$k)) {
        fatal_error ('You send invalid parameter');
        exit;
      }

      if (preg_match($jsvari->$key->$k,$v)) {
        $msg = sprintf('You can not access with %s[%s]=%s', $key, $k, $v);
        fatal_error($msg);
        exit;
      }
    }
    return;
  }

  if (!is_string ($jsvari->$key)) {
    fatal_error ('You send invalid parameter');
    exit;
  }

  if (preg_match($jsvari->$key,$value)) {
    $msg = sprintf('You can\\\'t access with %s=%s', $key, $value);
    fatal_error($msg);
    exit;
  }
  return;
}
?>
