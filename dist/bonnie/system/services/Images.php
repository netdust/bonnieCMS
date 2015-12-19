<?php

// Class to resize, crop and add water mark.
// needs php gd

// bugs ? report-me

// first release: dont recall, some day of 2005
// 2013 - pushed to github

/*
 *
// Load image
$image = new MyImage("image.JPG");
// Load water mark
$wm = new MyImage("water_mark.JPG");
// Ensure that water mark fits on image size
if ($wm->width > $image->width || $wm->height > $image->height) {
    $wv->scale_to_fit($image->width, $image->height);
}
// Merge in center with transparency 60%
$image->merge($wm, 'CENTER', 60);
// Save
$image->save($directory = "/home/images/",
                  $name = "image+wm",
                  $type = "JPG",
             $overwrite = true,
               $quality = 80);

$image = new MyImage("1.JPG");
$image->thumb(100, 75);
$image->save($directory = "/home/images/thumbs",
                  $name = "thumb",
                  $type = "JPG",
             $overwrite = true,
               $quality = 80);
 */

namespace services;

class Images {

    var $supported_img_types;

    var $type;
    var $width;  // the same that imagesx($image)
    var $height; // the same that imagesy($image)
    var $image;

    function __construct($file = null) {

        if (!$file) {
            trigger_error("Parameter file undefined.", E_USER_ERROR);
        }

        if (! file_exists($file)) {
            trigger_error("File '$file' not found.", E_USER_ERROR);
        }

        $this->check_php_gd();
        $this->supported_img_types = $this->supported_image_types();
        $this->open_image($file);
    }

    function open_image($file = null) {
        $image_type = $this->image_type($file);

        if (! $image_type) {
            trigger_error("Invalid image file: $file.", E_USER_ERROR);
        }

        $this->type   = $image_type[0];
        $this->width  = $image_type[1];
        $this->height = $image_type[2];

        if (! in_array($this->type, $this->supported_img_types)) {
            trigger_error("File type '$this->type' not supported.", E_USER_ERROR);
        }

        // Destroy if already open
        if ($this->image) {
            ImageDestroy($this->image);
        }

        switch ($this->type) {
            case 'JPG':
                $this->image = ImageCreateFromJPEG($file);
                break;
            case 'GIF':
                $this->image = ImageCreateFromGIF($file);
                break;
            case 'PNG':
                $this->image = ImageCreateFromPNG($file);
                break;
        }
    }

    function output( ) {
        header("Content-type:image/jpeg");
        // display image
        imagejpeg($this->image);
    }
    // api functions

    function resize($width = null, $height = null) {

        if (! is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: $width.", E_USER_ERROR);
        }

        if (! is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: $height.", E_USER_ERROR);
        }

        $temp = $this->image;

        $this->image = ImageCreateTrueColor($width, $height);

        ImageCopyResampled($this->image, $temp, 0, 0, 0, 0,
            $width+1, $height+1, $this->width, $this->height);

        ImageDestroy($temp);

        $this->width  = $width;
        $this->height = $height;

        return array($width, $height);
    }

    function resize_w($width = null) {
        if (! is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: $width.", E_USER_ERROR);
        }

        $height = $this->height * $width / $this->width;
        return $this->resize($width, $height);
    }

    function resize_h($height = null) {
        if (! is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: $height.", E_USER_ERROR);
        }

        $width = $this->width * $height / $this->height;
        return $this->resize($width, $height);
    }

    function scale_to_fit($width = null, $height = null) {
        if (! is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: $width.", E_USER_ERROR);
        }

        if (! is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: $height.", E_USER_ERROR);
        }

        $img_ratio = $this->width / $this->height;
        $ratio = $width / $height;

        if ($img_ratio <= $ratio) {
            return $this->resize_h($height);
        } else {
            return $this->resize_w($width);
        }
    }

    function scale($scale = null) {
        if (! is_numeric($scale) || $scale <= 0) {
            trigger_error("Invalid scale: $scale.", E_USER_ERROR);
        }
        $width	= $this->width	* $scale;
        $height = $this->height * $scale;
        return $this->resize($width, $height);
    }

    function crop($crop_width = 0, $crop_height = 0, $pos = 'CENTER') {

        if (! is_numeric($crop_width) || $crop_width < 0 || $crop_width >= $this->width) {
            trigger_error("Invalid crop width: $crop_width.", E_USER_ERROR);
        }

        if (! is_numeric($crop_height) || $crop_height < 0 || $crop_height >= $this->height) {
            trigger_error("Invalid crop height: $crop_height.", E_USER_ERROR);
        }

        $width	= $this->width	- $crop_width;
        $height = $this->height - $crop_height;

        list($x, $y) = $this->image_position(
            $this->width, $this->height, $width, $height, $pos);

        $temp = $this->image;

        $this->image = ImageCreateTrueColor($width, $height);

        ImageCopyResampled($this->image, $temp, 0, 0, $x, $y,
            $width+1, $height+1, $width+1, $height+1);

        ImageDestroy($temp);

        $this->width  = $width;
        $this->height = $height;

        return array($width, $height);
    }

    function thumb($width = null, $height = null) {

        $img_ratio   = $this->width / $this->height;

        if (! is_numeric($width) || $width < 1) {
            trigger_error("Invalid width: $width.", E_USER_ERROR);
        }

        if (! is_numeric($height) || $height < 1) {
            trigger_error("Invalid height: $height.", E_USER_ERROR);
            //$height = $width / $img_ratio;
        }

        if ($this->width >= $width && $this->height >= $height) {

            $thumb_ratio = $width / $height;

            if($img_ratio < $thumb_ratio) {
                $this->resize_w($width);
                $this->crop(0, floor($this->height - $height));
            } else {
                $this->resize_h($height);
                $this->crop(floor($this->width - $width), 0);
            }
        } else {
            if($this->width > $width){
                $this->crop(floor($this->width - $width), 0);
            }

            if($this->height > $height){
                $this->crop(0, floor($this->height - $height));
            }

            $temp = $this->image;

            list($x, $y) = $this->image_position(
                $width, $height, $this->width, $this->height);

            $this->image = ImageCreateTrueColor($width, $height);

            ImageCopy($this->image, $temp, $x, $y, 0, 0, $this->width, $this->height);

            ImageDestroy($temp);
        }

        $this->width  = $width;
        $this->height = $height;
    }

    function rotate( $d ) {
        $this->image = ImageRotate($this->image, $d, 0);
    }
    function set_transparent_color($r, $g, $b) {
        $r = $this->color($r);
        $g = $this->color($g);
        $b = $this->color($b);
        $color_index = ImageColorExact($this->image, $r, $g, $b);
        ImageColorTransparent($this->image, $color_index);
    }

    function merge($other = null, $pos = 'CENTER', $transparency = 50) {
        if (!is_a($other, 'MyImage')) {
            trigger_error("Parameter should be instance of MyImage.", E_USER_ERROR);
        }

        list($x, $y) = $this->image_position(
            $this->width, $this->height, $other->width, $other->height, $pos);

        ImageCopyMerge($this->image,
            $other->image,
            $x, $y,
            0, 0,
            $other->width, $other->height,
            100 - $transparency);
    }

    function save($directory = "",
                  $name = "",
                  $type = "",
                  $overwrite = true,
                  $quality = 80) {

        if ($directory) {
            if (!file_exists($directory)) {
                if (!@mkdir($directory, 0777)) {
                    trigger_error("Error creating directory '$directory'.", E_USER_ERROR);
                }
            }
        }

        if(! $name) {
            trigger_error("Enter a valid file name.", E_USER_ERROR);
        }

        if(! $type) {
            $type = $this->type;
        }

        if (! in_array($type, $this->supported_img_types)) {
            trigger_error("File type '$type' not supported. Use Only:(JPG, GIF, PNG).", E_USER_ERROR);
        }

        $filename = $directory.$name.".".$type;

        if(file_exists($filename) && !$overwrite) {
            trigger_error("Cannot overwrite.", E_USER_ERROR);
        }

        switch ($type){
            case 'JPG':
                ImageJPEG($this->image, $filename, $quality);
                break;
            case 'GIF':
                ImageGIF($this->image, $filename, $quality);
                break;
            case 'PNG':
                ImagePNG($this->image, $filename, $quality);
                break;
        }

        return $filename;
    }

    // auxiliary functions

    function check_php_gd() {
        if (! extension_loaded('gd')) {
            trigger_error("GD Libery Not Found.", E_USER_ERROR);
        }
    }

    function supported_image_types() {

        $supported = array();
        $possibles = array(IMG_GIF=>'GIF',
            IMG_JPG=>'JPG',
            IMG_PNG=>'PNG'
        );

        foreach($possibles as $i_type => $s_type) {

            if (imagetypes() & $i_type) {
                $supported[] =	$s_type;
            }
        }
        return $supported;
    }

    function image_type($file) {
        if(!$file || !@GetImageSize($file)) {
            return null;
        }

        $image_size = GetImageSize($file);
        $image_type = $image_size[2];

        $possibles = array(1=>'GIF',
            2=>'JPG',
            3=>'PNG',
            4=>'SWF',
            5=>'PSD',
            6=>'BMP',
            7=>'TIFF(intel byte order)',
            8=>'TIFF(motorola byte order)',
            9=>'JPC',
            10=>'JP2',
            11=>'JPX',
            12=>'JB2',
            13=>'SWC',
            14=>'IFF',
            15=>'WBMP',
            16=>'XBM');

        foreach($possibles as $key => $type) {

            if ($image_type == $key) {
                return array($type, $image_size[0], $image_size[1]);
            }
        }

        return null;
    }

    function image_position($width, $height, $width2, $height2, $pos = 'CENTER') {

        switch ($pos){
            case 'CENTER':
                $x = ($width  - $width2)  / 2;
                $y = ($height - $height2) / 2;
                break;
            case 'CENTER_RIGHT':
                $x = ($width  - $width2);
                $y = ($height - $height2) / 2;
                break;
            case 'CENTER_LEFT':
                $x = 0;
                $y = ($height - $height2) / 2;
                break;
            case 'TOP':
                $x = ($width  - $width2)  / 2;
                $y = 0;
                break;
            case 'TOP_RIGHT':
                $x = ($width  - $width2);
                $y = 0;
                break;
            case 'TOP_LEFT':
                $x = 0;
                $y = 0;
                break;
            case 'DOWN':
                $x = ($width  - $width2)  / 2;
                $y = ($height - $height2);
                break;
            case 'DOWN_RIGHT':
                $x = ($width  - $width2);
                $y = ($height - $height2);
                break;
            case 'DOWN_LEFT':
                $x = 0;
                $y = ($height - $height2);
                break;
            default:
                $x = ($width  - $width2)  / 2;
                $y = ($height - $height2) / 2;
        }
        return array($x, $y);
    }

    function color($color) {
        if ($color < 0) {
            $color = 0;
        }
        else if ($color > 255) {
            $color = 255;
        }
        return $color;
    }
}

?>