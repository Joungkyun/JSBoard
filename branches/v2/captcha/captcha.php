<?php
# $Id: captcha.php,v 1.4 2014-03-02 17:11:30 oops Exp $

$incpath = ini_get ('include_path');
ini_set ('include_path', "$incpath:" . dirname (__FILE__));
if ( version_compare (PHP_VERSION, '6.0.0', '<') ) {
  define ('FILE_USE_INCLUDE_PATH', true);
  define ('FILE_TEXT', true);
}

Class Captcha extends Captchaimg {
  private $data_r;
  private $keysize = 1000;
  public $disable = false;

  function __construct ($datafile) {
    if ( ($data = file_get_contents ($datafile, FILE_USE_INCLUDE_PATH|FILE_TEXT)) === false ) {
      $this->disable = true;
      return;
    }

    $this->data_r = unserialize ($data);
    if ( ! is_array ($this->data_r) || ! count ($this->data_r) )
      $this->disable = true;
  }

  /*
   * PHP 4 object constructor
   */
  function Captcha ($datafile) {
    $this->__construct ($datafile);
  }

  function captchakey() {
    return rand (0, $this->keysize);
  }

  function captchadata ($key = 0) {
    if ( $key == "" )
      return '00000';

    if ( $key > 999 || $key < 0 )
      return '00000';

    return $this->data_r[$key];
  }

  function check ($key, $value = '00000') {
    if ( $value == '00000' )
      return FALSE;

    $value = strtoupper ($value);
    $confirm = $this->captchadata ($key);

    if ( $confirm == '00000' )
      return FALSE;

    if ( $confirm != $value )
      return FALSE;

    return TRUE;
  }

  function __destruct() {
    unset ($this->data_r);
  }
}

# http://sam.zoy.org/pwntcha/
Class Captchaimg {
  public $img = array (
        'type' => 'png',
        'width' => 88,
        'height' => 30,
        'bg' => array (255, 255, 255),
        'border' => array (0, 0, 0)
      );
  public $str = array (
        'size' => 8,
        'color' => array (0, 0, 0),
        'bgcolor' => array (101, 101, 101)
      );
  #public $font = 'font/A.D.MONO.ttf';
  #public $font = 'font/Abbey-Medium.ttf';
  #public $font = 'font/Abscissa.ttf';
  #public $font = 'font/Accidental.Presidency.ttf';
  #public $font = 'font/Acid.Reflux.BRK.ttf';
  public $font = 'font/AcidDreamer.ttf';

  function print_img_header () {
    header ('Content-type: image/' . $this->img['type']);
  }

  function create_noise ($im, $count = 1) {
    if ( ! is_resource ($im) )
      return;

    while ( $count-- ) {
      $cx = rand (10, 40);
      $cy = rand (0, 20);
      $he = rand (-30, 30);
      $bg = imageColorAllocate ($im, rand (1,255), rand (1,255), rand (1,255));
      $start = rand (25, 50);
      imageArc ($im, $cx, $cy, 100, $he, $start, 155, $bg);
    }
  }

  function create_cercle ($im, $count = 1) {
    if ( ! is_resource ($im) )
      return;

    while ( $count-- ) {
      if ( ($count % 2) == 1 ) {
        $x = rand (-20, 30);
        $y = rand (-30, 40);
      } else {
        $x = rand (20, 60);
        $y = rand (30, 70);
      }
      $bg = imageColorAllocate ($im, rand (15, 200), rand (40,220), rand (150, 255));
      imagefilledellipse ($im, $x, $y, 100, 100, $bg);
    }
  }

  function create_captcha ($str = '00000') {
    $im     = imagecreatetruecolor ($this->img['width'], $this->img['height']);
    $border = imageColorAllocate ($im, $this->img['border'][0], $this->img['border'][1], $this->img['border'][2]);
    $bg     = imageColorAllocate ($im, $this->img['bg'][0], $this->img['bg'][1], $this->img['bg'][2]);
    $fg     = imageColorAllocate ($im, $this->str['color'][0], $this->str['color'][1], $this->str['color'][2]);
    $fbg    = imageColorAllocate ($im, $this->str['bgcolor'][0], $this->str['bgcolor'][1], $this->str['bgcolor'][2]);

    imageFilledRectangle ($im, 0, 0, $this->img['width'] - 1, $this->img['height'] - 1, $bg);
    imageRectangle ($im, 0, 0, $this->img['width'] - 1, $this->img['height'] - 1, $border);

    $this->create_cercle ($im, 2);
    $this->create_noise ($im, 4);

    $_x = array (rand (3, 8), rand (15, 22), rand (35, 44), rand (49, 56), rand (63, 75));
    $_y = array (rand (15, 30), rand (15, 30),  rand (14, 30), rand (15, 30),  rand (14, 30));

    for ( $x=0; $x<strlen ($str); $x++ ) :
      $angle = rand (-20, 20);
      imagettftext ($im, 12, $angle, $_x[$x] + 2, $_y[$x] + 3, $fbg, $this->font, $str[$x]);
      imagettftext ($im, 12, $angle, $_x[$x], $_y[$x], $fg, $this->font, $str[$x]);
      #imageString ($im, 5, $_x[$x] + 2, $_y[$x] + 3, $str[$x], $fbg);
      #imageString ($im, 5, $_x[$x], $_y[$x], $str[$x], $fg);
    endfor;

    #imageString ($im, 5, 19, 9, $str, $fbg);
    #imageString ($im, 5, 17, 7, $str, $fg);

    switch ($this->img['type']) :
      case 'jpg' :
        imageJpeg ($im);
        break;
      case 'png' :
        imagePng ($im);
        break;
      default :
        imageGif ($im);
    endswitch;
    imageDestroy ($im);
  }

  function __destruct() {
    unset ($this->img);
    unset ($this->str);
  }
}

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
