<?php
class CropAvatar {
    private $src;
    private $data;
    private $dst;
    private $type;
    private $extension;
    private $msg;
    
    function __construct($src, $data, $file) {//echo json_encode(array($_POST['avatar_data'],$_POST['avatar_src'],$_FILES['avatar_file'],000));die();
        $this -> setSrc($src);
        $this -> setData($data);
        $this -> setFile($file);
        $this -> crop($this -> src, $this -> dst, $this -> data);
    }
    
    private function setSrc($src) {//echo json_encode(array($_POST['avatar_data'],$_POST['avatar_src'],$_FILES['avatar_file'],111));die();
        if (!empty($src)) {
            $type = exif_imagetype($src);
            
            if ($type) {
                $this -> src = $src;
                $this -> type = $type;
                $this -> extension = image_type_to_extension($type);
                $this -> setDst();
            }
        }
    }
    
    private function setData($data) {//echo json_encode(array($_POST['avatar_data'],$_POST['avatar_src'],$_FILES['avatar_file'],222));die();
        if (!empty($data)) {
            $this -> data = json_decode(stripslashes($data));
        }
    }
    
    
    private function setFile($file) {
        //       echo json_encode(array($_POST['avatar_data'],$_POST['avatar_src'],$_FILES['avatar_file'],333));die();
        $errorCode = $file['error'];
        
        if ($errorCode === UPLOAD_ERR_OK) {
            //         echo json_encode($file['tmp_name']);die();
            //       $type = exif_imagetype($file['tmp_name']);
            //         echo json_encode($type);die();
            //         $type = getImageType($file['type']);
            $type;
            switch($file['type']){
                case 'image/gif' :  $type= 1 ;break;
                case 'image/jpeg' : $type= 2 ;break;
                case 'image/png' :  $type= 3 ;break;
                default: break;
            }
            //          echo json_encode($type);die();
            if ($type) {
                $extension = image_type_to_extension($type);
                
                // $src = 'images/' . date('YmdHis') . '.original' . $extension;
                $src = 'assets/images/imgup/profile/' . date('YmdHis') . '.original' . $extension;
                
                if ($type ==  IMAGETYPE_GIF  || $type ==  IMAGETYPE_JPEG  || $type ==  IMAGETYPE_PNG ) {
                    
                    if (file_exists($src)) {
                        unlink($src);
                    }
                    
                    $result = move_uploaded_file($file['tmp_name'], $src);
                    
                    if ($result) {
                        $this -> src = $src;
                        $this -> type = $type;
                        $this -> extension = $extension;
                        $this -> setDst();
                    } else {
                        $this -> msg = 'Failed to save file';
                    }
                    
                } else {
                    $this -> msg = 'Please upload image with the following types: JPG, PNG, GIF';
                }
            } else {
                $this -> msg = 'Please upload image file';
            }
        } else {
            $this -> msg = $this -> codeToMessage($errorCode);
        }
        //     /$this -> msg =" testttt : ".$file['error'];
    }
    
    
    private function getImageType($file){
        switch($file){
            case 'image/gif' :  return 1 ;break;
            case 'image/jpeg' : return 2 ;break;
            case 'image/png' :  return 3 ;break;
            default: break;
        }
    }
    
    private function setDst() {
        // $this -> dst = 'images/' . date('YmdHis') . '.jpg';
        $this -> dst = 'assets/images/imgup/profile/' . date('YmdHis') . '.jpg';
    }
    
    private function crop($src, $dst, $data) {
        if (!empty($src) && !empty($dst) && !empty($data)) {
            switch ($this -> type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($src);
                    break;
                    
                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($src);
                    break;
                    
                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($src);
                    break;
            }
            
            if (!$src_img) {
                $this -> msg = "Failed to read the image file";
                return;
            }
            
            $size = getimagesize($src);
            $size_w = $size[0]; // natural width
            $size_h = $size[1]; // natural height
            
            $src_img_w = $size_w;
            $src_img_h = $size_h;
            
            $degrees = $data -> rotate;
            
            // Rotate the source image
            if (is_numeric($degrees) && $degrees != 0) {
                // PHP's degrees is opposite to CSS's degrees
                $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );
                
                imagedestroy($src_img);
                $src_img = $new_img;
                
                $deg = abs($degrees) % 180;
                $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
                
                $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
                $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
                
                // Fix rotated image miss 1px issue when degrees < 0
                $src_img_w -= 1;
                $src_img_h -= 1;
            }
            
            $tmp_img_w = $data -> width;
            $tmp_img_h = $data -> height;
            $dst_img_w = $data -> width;
            $dst_img_h = $data -> height;
            /*
             $dst_img_w = 250;
             $dst_img_h = 250;
             */
            
            $src_x = $data -> x;
            $src_y = $data -> y;
            
            if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
                $src_x = $src_w = $dst_x = $dst_w = 0;
            } else if ($src_x <= 0) {
                $dst_x = -$src_x;
                $src_x = 0;
                $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
            } else if ($src_x <= $src_img_w) {
                $dst_x = 0;
                $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
            }
            
            if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
                $src_y = $src_h = $dst_y = $dst_h = 0;
            } else if ($src_y <= 0) {
                $dst_y = -$src_y;
                $src_y = 0;
                $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
            } else if ($src_y <= $src_img_h) {
                $dst_y = 0;
                $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
            }
            
            // Scale to destination position and size
            $ratio = $tmp_img_w / $dst_img_w;
            $dst_x /= $ratio;
            $dst_y /= $ratio;
            $dst_w /= $ratio;
            $dst_h /= $ratio;
            
            $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
            
            // Add transparent background to destination image
            imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
            imagesavealpha($dst_img, true);
            
            $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
            
            if ($result) {
                if (!imagepng($dst_img, $dst)) {
                    $this -> msg = "Failed to save the cropped image file";
                }
            } else {
                $this -> msg = "Failed to crop the image file";
            }
            
            imagedestroy($src_img);
            imagedestroy($dst_img);
        }
    }
    
    private function codeToMessage($code) {
        $errors = array(
            UPLOAD_ERR_INI_SIZE =>'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE =>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL =>'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE =>'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR =>'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE =>'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION =>'File upload stopped by extension',
        );
        
        if (array_key_exists($code, $errors)) {
            return $errors[$code];
        }
        
        return 'Unknown upload error';
    }
    
    public function getResult() {
        return !empty($this -> data) ? $this -> dst : $this -> src;
    }
    
    public function getMsg() {
        return $this -> msg;
    }
}
echo json_encode(array($_POST['avatar_data'],$_POST['avatar_src'],$_FILES['avatar_file'],000));die();
// print_t($_POST);die;

$crop = new CropAvatar(
    isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
    isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
    isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
    );

$typeImgFile = basename($_FILES["avatar_file"]["name"]);
$imageFileType = strtolower(pathinfo($typeImgFile,PATHINFO_EXTENSION));

$response = array(
    'state'  => 200,
    'message' => $crop -> getMsg(),
    'result' => $crop -> getResult(),
    'type' => $imageFileType
);
// 20170819120202

/* ==== code for delete image original ==== */
$imgFile = $crop -> getResult();
$fileImg = substr($imgFile, 24, 14);  //  change substr to real location path
if( $imageFileType == "png"){
    $fileDel = $fileImg.".original.".$imageFileType;
}else{
    $fileDel = $fileImg.".original.jpeg";
}

$pathFile = $_SERVER['DOCUMENT_ROOT']."/agent-card/assets/images/imgup/profile/".$fileDel;
unlink($pathFile);

echo json_encode($response);
