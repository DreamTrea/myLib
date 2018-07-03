<?php
/**
 * printing image source data
 * that target was img tag. so you can use like this
 * print_image($data['file_path']);
 */
function print_image($path){
  $image = new Imagick();
  $image->readImage(_LOCAL_.$path);
  $height = $image->getImageHeight();
  $width = $image->getImageWidth();

  $canvas = new Imagick();
  $canvas->newImage($width,$height,new ImagickPixel('white'));
  $canvas->setImageFormat('png');
  $canvas->compositeImage($image,imagick::COMPOSITE_OVER, 0, 0);

  header('Content-type: image/png');
  echo $canvas;
  $image->destroy();
  $canvas->destroy();
}

/**
 * printing image source data with water mark
 * that target was img tag. so you can use like this
 * print_water_marked_image($data['file_path']);
 */
function print_water_marked_image($path){
  $image = new Imagick();
  $image->readImage(_LOCAL_.$path);

  $height = $image->getImageHeight();
  $width = $image->getImageWidth();

  $watermark = new Imagick();
  $watermark->readImage($_SERVER[DOCUMENT_ROOT].__WATER_MARK_PATH__);
  $watermark = $watermark->flattenImages();
  $watermark->setImageOpacity(0.3);
  $watermark->setImageOrientation(Imagick::COLOR_ALPHA);

  $water_height = $watermark->getImageHeight();
  $water_width = $watermark->getImageWidth();

  $canvas = new Imagick();
  $canvas->newImage($width,$height,new ImagickPixel('white'));
  $canvas->setImageFormat('png');
  $canvas->compositeImage($image,imagick::COMPOSITE_OVER, 0, 0);

  $cal_margin_width = ($water_width-($width%$water_width))/2;
  $cal_margin_height = ($water_height-($height%$water_height))/2;
  $count_x = ($width-$width%$water_width)/$water_width;
  $count_y = ($height-$height%$water_height)/$water_height;
  for($i=0; $i<=$count_x;$i++){
    for ($j=0; $j <=$count_y; $j++) {
      $j+=2;
      if(!($i%2)){
        $canvas->compositeImage($watermark,imagick::COMPOSITE_OVER, $i*$water_width-$cal_margin_width, $j*$water_height-$cal_margin_height);
      }
    }
  }

  header('Content-type: image/png');
  echo $canvas;
  $image->destroy();
  $watermark->destroy();
  $canvas->destroy();
}

/**
 * filtering image files. with out image that will be return false
 * if giving width and height parameter, then that image(checking image file) will be resized.
 * tested gif, png, jpg
 */
function imageCheck($target,$width=1,$height=1){
  if($width==1&&$height==1){
    return is_array(getimagesize($target));
  }else{
    $rvalue = false;
    if(is_array(getimagesize($target))){
      try {
        $img = new Imagick($target);
        if(strtoupper($img->getImageFormat())=='GIF'){
          $img = $img->coalesceImages();
          $img = $img->coalesceImages();
          do {
            if($width==0||$height==0)
                  $img->resizeImage($width, $height, Imagick::FILTER_BOX, 1);
            else  $img->resizeImage($width, $height, Imagick::FILTER_BOX, 1,true);
          } while ($img->nextImage());
          $img = $img->deconstructImages();
          $img->writeImages($target,true);
        }else{
          if($width==0||$height==0)
                $img->thumbnailImage($width, $height);
          else  $img->thumbnailImage($width, $height,true);
          $img->writeImage($target);
        }
        $img->destroy();
        $rvalue = true;
      } catch (Exception $e) {
      }
    }
    return $rvalue;
  }
}
?>
