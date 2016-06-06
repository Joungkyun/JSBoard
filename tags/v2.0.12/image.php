<?php
include "include/print.php";
include "include/check.php";
include "include/error.php";
parse_query_str();

$ImgPath = rawurldecode($path);
$ImgType = check_filetype($ImgPath);

# 원본 이미지로 부터 JPEG 파일을 생성
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

# 원본 이미지의 width, height 를 구함
$owidth = ImagesX($img);
$oheight = ImagesY($img);

# width 와 height 를 모두 0 으로 주었을 경우 기본값 50
if(!$width && !$height) $width = $height = 50;

# width 가 없을 경우 height 의 축소/확대 비율로 width 를 구함
if(!$width) {
  $ratio = ((real)$height/$oheight);
  $width = ((int)$owidth*$ratio);
}

# height 가 없을 경우 width 의 축소/확대 비율로 height 를 구함
if(!$height) {
  $ratio = ((real)$width/$owidth);
  $height = ((int)$oheight*$ratio);
}

# 새로운 이미지를 생성
$newimg = ImageCreate($width,$height);
# 새로운 이미지에 원본 이미지를 사이즈 조정하여 복사.
ImageCopyResized($newimg,$img,0,0,0,0,$width,$height,$owidth,$oheight);

# 타입에 따라 헤더를 출력
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
