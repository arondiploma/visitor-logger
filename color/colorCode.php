<?php
include("colorCodeConverter.php");
header("Content-Type: image/png");
$im = @imagecreate(18, 18) or die("Cannot Initialize new GD image stream");
$hex = $_REQUEST['color'];
$rgb = hexTOrgb($hex);

$background_color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
imagepng($im);
imagedestroy($im);
?>
