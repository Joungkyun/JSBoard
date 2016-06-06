<?php
include "include/print.php";
include "include/check.php";
include "include/error.php";
parse_query_str();

$ImgPath = rawurldecode($path);
$ImgType = check_filetype($ImgPath);

# ���� �̹����� ���� JPEG ������ ����
$otype = GetImageSize($ImgPath);
switch($otype[2]) {
  case 1:
    $img = ImageCreateFromGIF($ImgPath);
    break;
  case 2:
    $img = ImageCreateFromJPEG($ImgPath);
    break;
  case 3:
    $img = ImageCreateFromPNG($ImgPath);
    break;
  default:
    print_error("Enable ImgPath file is type of GIF,JPG,PNG",250,250,1);
}

# ���� �̹����� width, height �� ����
$owidth = ImagesX($img);
$oheight = ImagesY($img);

# width �� height �� ��� 0 ���� �־��� ��� �⺻�� 50
if(!$width && !$height) $width = $height = 50;

# width �� ���� ��� height �� ���/Ȯ�� ������ width �� ����
if(!$width) {
  $ratio = ((real)$height/$oheight);
  $width = ((int)$owidth*$ratio);
}

# height �� ���� ��� width �� ���/Ȯ�� ������ height �� ����
if(!$height) {
  $ratio = ((real)$width/$owidth);
  $height = ((int)$oheight*$ratio);
}

# ���ο� �̹����� ����
$newimg = ImageCreate($width,$height);
# ���ο� �̹����� ���� �̹����� ������ �����Ͽ� ����.
ImageCopyResized($newimg,$img,0,0,0,0,$width,$height,$owidth,$oheight);

# Ÿ�Կ� ���� ����� ���
switch($ImgType) {
  case "wbmp" :
    $type_header = "vnd.wap.wbmp";
    break;
  default :
    $ImgType = ($ImgType == "jpg") ? "jpeg" : $ImgType;
    $type_header = $ImgType;
}
Header("Content-type: image/$type_header");

switch($ImgType) {
  case "png" :
    ImagePNG($newimg);
    break;
  case "wbmp" :
    ImageWBMP($newimg);
    break;
  case "gif" :
    ImageGIF($newimg);
  default :
    ImageJPEG($newimg,'',80);
}
ImageDestroy($newimg);
?>
