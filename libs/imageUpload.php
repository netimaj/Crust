<?php
/**
* Image Upload Class
* @author Ahmet Özışık
* 30 March 2011
* 
*/

class imageUpload
{
  // 1 mb
  private $max_filesize = 5048576;
  
  public $error;
  
  private $image_width;
  private $image_height;
  
  private $file_types = array
    (
      'image/pjpeg' => 'jpg',
      'image/jpeg' => 'jpg',
      'image/gif' => 'gif',
      'image/png' => 'png'
    );
  
  public $resize_width;
  public $resize_height;
  
  private $image;
  
  public $name;
  
  private $tmp_path;
  
  private $upload_path;
  public  $extension;
  
  public function upload($file_, $upload_path, $resize_width=0, $resize_height=0, $key)
  {
    if(is_array($file_))
    {
      $file = array();
      
      foreach($file_ as $k => $v)
      {
        $file[$k] = '';
        if(is_array($v))
        {
          foreach($v as $y)
            $file[$k] = $y;          
        }
        else
        {
          $file = $file_;
        }
  
      }
    }
    if($file['size'] > $this->max_filesize) return 'Dosya boyutu çok büyük';
    // Upload path is broken
    if(!file_exists($upload_path) or !is_dir($upload_path)) return 'Upload gerçekleşmedi';
    // An error occured during the upload
    if($file['error'] > 0) return 'Upload sırasında bir sorun oluştu';
    // Uploaded file is not a image                 
    if(!isset($this->file_types[$file['type']])) return 'Yüklemeye çalıştığınız dosya bir resim değil';
    // Set tmp path
    $this->tmp_path = $file['tmp_name'];
    // If file not uploaded by HTTP-POST
    if(!is_uploaded_file($this->tmp_path)) return 'Kötü amaçlı kullanım tespit edildi';
    // Create image from tmp file
    $run = $this->file_types[$file['type']];
    $this->$run();
    
    $size = getimagesize($file['tmp_name']);
    $this->image_width = $size[0];
    $this->image_height = $size[1];
    
    // Delete tmp file
    unlink($this->tmp_path);
                                                                         
    $this->name = md5($key).'.jpg';
    
    
    if($resize_width != 0 and $resize_height != 0) $this->resizeImage($resize_width, $resize_height);    
    
    $this->save($upload_path.'/'.$this->name);
    return true;
  }
  
  private function save($path)
  {
    imagejpeg($this->image, $path, 100);
  }
  
  private function resizeImage($width, $height)
  {
    $resample = imagecreatetruecolor($width, $height);
    imagecopyresampled($resample, $this->image,0,0,0,0,$width,$height,$this->image_width, $this->image_height);
    $this->image = $resample;
    return;
  }
                         
  private function jpg() { $this->image = imagecreatefromjpeg($this->tmp_path); }
  private function gif() { $this->image = imagecreatefromgif($this->tmp_path); }
  private function png() { $this->image = imagecreatefrompng($this->tmp_path); }
  

  
}


?>