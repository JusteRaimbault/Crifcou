<?php
session_start();

header('Content-type: image/jpeg');

//captcha of type "x+y" wher x,y \in [|0,99|]
$n1 = rand(0,100);
$n2 = rand(0,100);
$_SESSION['captcha'] = $n1 + $n2 ;

$s = ((string)$n1)." + ".((string)$n2);

// select the font. '4' is a builtin kind,
// with each letter about 8px wide
$font = 4;
$width = strlen($s) * 8; // 8px wide per letter
$height = 16; // this font size needs about this height

// create the GD image
$im = imagecreate($width, $height);

// allocate the background colour. The first call
// to this function sets the background
$white = imagecolorallocate($im, 255, 255, 255);

// the text colour.. black
$textColor = imagecolorallocate($im, 0, 0, 0);

// write the email address to the image
imagestring($im, $font, 0, 0, $s, $textColor);


// output the content-type header and the image

imagejpeg($im);


?>
