<?php 
session_start();

function makeCaptcha() {
	putenv('GDFONTPATH=' . realpath('.'));

	//Make a Blank Image
	$im = imagecreatetruecolor(150, 50);
	$white = imagecolorallocate($im, 255, 255, 255);
	imagefilledrectangle($im, 0, 0, 150, 50, $white);
	$color1 = imagecolorexact($im, rand(100,150), rand(100,150),rand(100,150));
	$color2 = imagecolorexact($im, rand(100,150), rand(100,150),rand(100,150));
	$color3 = imagecolorexact($im, rand(100,150), rand(100,150),rand(100,150));
	$color4 = imagecolorexact($im, rand(100,150), rand(100,150),rand(100,150));
	$color5 = imagecolorexact($im, rand(100,150), rand(100,150),rand(100,150));
	$s1 = genRandomString();
	$s2 = genRandomString();
	$s3 = genRandomString();
	$s4 = genRandomString();
	$s5 = genRandomString();
	$code = $s1 . $s2 . $s3 . $s4 . $s5;
	$_SESSION['captcha'] = '';
	$_SESSION['captcha'] = $code;
	$i=1;
	while($i<=40) {
	imageline($im, rand(0,150), rand(0,50), rand(0,150), rand(0,50), imagecolorexact($im, rand(100,200), rand(100,200),rand(100,200)));
	$i++;
	}
					
	$font = "font.ttf";
	//Add the first Character
	imagefttext($im,30, rand(-20,20), 10, 35, $color1, $font, $s1);
	imagefttext($im,30, rand(-20,20), 35, 35, $color2, $font, $s2);
	imagefttext($im,30, rand(-20,20), 55, 35, $color3, $font, $s3);
	imagefttext($im,30, rand(-20,20), 80, 35, $color4, $font, $s4);
	imagefttext($im,30, rand(-20,20), 105, 35, $color5, $font, $s5);
	header('Content-type: image/jpeg');
	imagejpeg($im); 
}

function  genRandomString() {
    $length = 1;
    $characters = "23456789abcdefghijkmnpqrstuvwxyz";
    $string = "";    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    if ($string == "") {
    	genRandomString();
    } else {
		return $string;
    }
}


makeCaptcha();
?>