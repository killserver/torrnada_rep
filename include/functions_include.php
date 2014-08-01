<?php

function view_saves($text) {
	return str_replace(array("&quot;","&amp;nbsp;","&nbsp;","&amp;"), array("\"", " ", " ", "&"), $text);
}

function watermark($path) {
    global $watermark;
    if ($watermark && $path) {
        $mywatermark = win2uni($watermark);
        preg_match('#(.*?)\.(.*?)\\z#si', $path, $matches);
        $type = strtolower($matches [2]);
        $shrift_weight = 10;
        $shrift_rotate = 0;
        $shrift_template = "./torrentbar/eraser.ttf";
        $template_file = $path;
        //===========================================================================
        // Main body
        //===========================================================================
        if (!file_exists($shrift_template))
            die("Can`t loading shrift " . $shrift_template);
        if ($type == 'jpg')
            $type = 'jpeg';
        $str = 'imagecreatefrom' . $type;
        $img = $str($template_file) or die("Cannot Initialize new GD image stream! " . $path);
        $WIDTH = imagesx($img);
        $HEIGHT = imagesy($img);
        $bbox = imagettfbbox($shrift_weight, $shrift_rotate, $shrift_template, $mywatermark);
        $watermark_x = $WIDTH - ($bbox [2] - $bbox [0]) - 5;
        $watermark_y = $HEIGHT - 5;
        //print ( $watermark_x . "<br/>" );
        //print ( $watermark_y );
        $totalcolor = imagecolorat($img, $watermark_x, $watermark_y);
        $rgb = imagecolorsforindex($img, $totalcolor);
        $color = rgb2hex($rgb ['red'], $rgb ['green'], $rgb ['blue']);
        imagettftext($img, $shrift_weight, $shrift_rotate, $watermark_x, $watermark_y, $color, $shrift_template, $mywatermark);
        //header ( "Content-type: image/png" );
        //$str = 'image' . $type;
        //$str ( $img );
        //imagedestroy ( $img );
        //die ();
        ob_start ();
        $str = 'image' . $type;
        $str($img);
        $image = ob_get_contents ();
        ob_end_clean ();
        $file = fopen($path, 'w');
        fwrite($file, $image);
        fclose($file);
        //print ( $image );
        imagedestroy($img);
    }
}


?>