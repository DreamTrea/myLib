<?php
/**
 * create directory tree with out base(parent) directory
 */
function mkdir_tree($path){
  $path = explode('/',$path);
  $path_buff = '';
  foreach($path as $v){
    if($path_buff=='')  $path_buff = $v;
    else                $path_buff .= '/'.$v;
    @mkdir(_LOCAL_.'/upload/'.$path_buff);
    @chmod(_LOCAL_.'/upload/'.$path_buff,0707);
  }
}
/**
 * cleaning upload file path
 */
function check_upload_path($target,$name,$fname=""){
  try{
    if(is_array($name)){
      @unlink(_LOCAL_.'/upload/'.$target.'/'.$name[$fname]);
      unset($name[$fname]);
      return $name;
    }else{
      @unlink(_LOCAL_.'/upload/'.$target.'/'.$name);
      return true;
    }
  }catch(Exception e){
    return false;
  }
}
/**
 * get mime type from file
 */
function get_mime($f) {
  $mime = shell_exec("file -bi ".$f);
  return $mime;
}
/**
 * upload files and save renaming working are going on here.
 */
function file_upload($upload_path,$imageOnly){
  global $_FILES;

  if($upload_path==""){
    throw new Exception("upload_path is undefined");
  }

  $file_list = array();
  foreach ($_FILES as $k => $v) {
    if($v['size']>0){
      $file = array();
      $file[] = $k;
      $file[] = $v['name'];
      $file[] = date("YmdHis").sprintf("%04d",rand(1,9999)).strstr($v['name'],'.');
      if($imageOnly){
        if(!imageCheck($v['tmp_name'])){
          @unlink($v['tmp_name']);
          throw new Exception("uploaded file is not a valid image.");
        }
      }else{
        //if(check_real_mime($v['tmp_name'])){
        if(is_executable($v['tmp_name'])){
          @unlink($v['tmp_name']);
          throw new Exception("that is wrong type of file");
        }
      }
      @mkdir(_LOCAL_._UPLOAD_DIR_);
      @chmod(_LOCAL_._UPLOAD_DIR_,0707);
      mkdir_tree($upload_path);
      rename($v['tmp_name'],_LOCAL_._UPLOAD_DIR_.$upload_path.'/'.$file[2]);
      @chmod(_LOCAL_._UPLOAD_DIR_.$upload_path.'/'.$file[2],0707);
      $file_list[] = $file;
    }
  }
  return $file_list;
}
?>
