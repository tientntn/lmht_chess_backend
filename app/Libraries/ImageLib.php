<?php namespace App\Libraries;

use File, Image, Input;

class ImageLib {

  public static function upload_image($file, $dirictory, $fileName, $resizes = array(), $crop = 0, $type='.jpg') {
    if (is_dir($dirictory) != true){
      $permission = intval('0777', 8);
      File::makeDirectory($dirictory, $permission, true);
    }
    $dirictory = $dirictory.'/';
    $file->move($dirictory, $fileName.$type);
    //$imgsource = Image::make($file);
    //$imgsource->save($dirictory.$fileName.$type);
    if (!empty($resizes)) {
      foreach ($resizes as $resize) {
        $size = explode("x",$resize);
        $width = $size[0];
        $height = $size[1];
        if ($crop == 0) {
          $img = Image::make($dirictory.$fileName.$type);
          $img->resize($width, $height, function ($constraint) { 
            $constraint->aspectRatio();
          });
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');
        } else {
          $img = Image::make($dirictory.$fileName.$type);
          $img->fit($width, $height);
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');
        }
      }
    }
    File::move($dirictory.$fileName.$type, $dirictory.$fileName.'.png');
  }

  public static function ajax_upload_image($file, $dirictory, $fileName) {
    if (is_dir($dirictory) != true){
      $permission = intval('0777', 8);
      File::makeDirectory($dirictory, $permission, true);
    }
    $dirictory = $dirictory.'/';
    //$file->move($dirictory, $fileName.$type);
    $imgsource = Image::make($file);
    $imgsource->save($dirictory.$fileName);    
  }

  public static function delete_image($dirictory, $fileName, $resizes = array(), $type='.png') {
    // echo 'a';
    //echo $dirictory.'/'.$fileName.$type;die;
    File::delete($dirictory.'/'.$fileName.$type);
    if (!empty($resizes)) {
      foreach ($resizes as $resize) {
        $size = explode("x",$resize);
        $width = $size[0];
        $height = $size[1];
        File::delete($dirictory.'/'.$fileName.'_'.$resize.$type);
      }
    }
  }

  public static function delete_folder($directory) {
    File::deleteDirectory($directory);
  }

  public static function upload_image_not_source($file, $dirictory, $fileName, $resizes = array(), $crop = 0, $type='.jpg') {
    if (is_dir($dirictory) != true){
      $permission = intval('0777', 8);
      File::makeDirectory($dirictory, $permission, true);
    }
    $dirictory = $dirictory.'/';
    $img = Image::make($file);
   // $imgsource->save($dirictory.$fileName.$type);
    if (!empty($resizes)) {
      foreach ($resizes as $resize) {
        $size = explode("x",$resize);
        $width = $size[0];
        $height = $size[1];
        if ($crop == 0) {
         // $img = Image::make($dirictory.$fileName.$type);
          $img->resize($width, $height, function ($constraint) { 
            $constraint->aspectRatio();
          });
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');
        } else {
         // $img = Image::make($dirictory.$fileName.$type);
          $img->fit($width, $height);
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');
        }
      }
    }
  }

  public static function createSizeImage($fileName, $width, $height) {
    $url = config('image.image_url').'/'.$fileName;
    $path = config('image.image_root').'/'.$fileName;

    if(ImageLib::get_http_response_code($url.'.png') == "200"){
      $img = Image::make($path.'.png');
      $img->resize($width, $height, function ($constraint) { 
        $constraint->aspectRatio();
      });
      $img->save($path.'_'.$width.'x'.$height.'.jpg');
      File::move($path.'_'.$width.'x'.$height.'.jpg', $path.'_'.$width.'x'.$height.'.png');
    }
  }

  public static function upload_image_replace_source_by_size($file, $dirictory, $fileName, $resizes = array(), $crop = 0, $type='.jpg', $sizeReplace = '') {
    if (is_dir($dirictory) != true){
      $permission = intval('0777', 8);
      File::makeDirectory($dirictory, $permission, true);
    }
    $dirictory = $dirictory.'/';
    //$file->move($dirictory, $fileName.$type);
    $imgsource = Image::make($file);
    $imgsource->save($dirictory.$fileName.$type);
    if (!empty($resizes)) {
      foreach ($resizes as $resize) {
        $size = explode("x",$resize);
        $width = $size[0];
        $height = $size[1];
        if ($crop == 0) {
          $img = Image::make($dirictory.$fileName.$type);
          $img->resize($width, $height, function ($constraint) { 
            $constraint->aspectRatio();
          });
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');

        } else {
          $img = Image::make($dirictory.$fileName.$type);
          $img->fit($width, $height);
          $img->save($dirictory.$fileName.'_'.$resize.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');

        }
        //replace source by image size
        if (!empty($sizeReplace)) {
          $r_size = explode("x",$sizeReplace);
          $r_width = $r_size[0];
          $r_height = $r_size[1];
          $img = Image::make($dirictory.$fileName.$type);
          $img->resize($r_width, $r_height, function ($constraint) { 
            $constraint->aspectRatio();
          });
          $img->save($dirictory.$fileName.$type);
          File::move($dirictory.$fileName.'_'.$resize.$type, $dirictory.$fileName.'_'.$resize.'.png');

        }
      }
    }
    File::move($dirictory.$fileName.$type, $dirictory.$fileName.'.png');
  }

  public static function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
  }

}
