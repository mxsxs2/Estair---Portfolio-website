<?php
/*-------------------------
 Firs: call the object
    $obj= new ImageUploader($the_error_language_pack);
 Second: set the path where we wanna upload the image
    $obj->Dir_path("the_path/of/the/dir", "the_thumbnail/path");
    The thumbnail path will be concated with the first path.
 Third:
    $obj->Image_uploader($the_image,$replace_the_image_or_not,$image_name);
    The image can be a string ulr, or an array from upload.
    The image replacement is definetely true,
    The image name is definetely "". It is mean we want to keep the original name.
    If we give something to this var, that will be name of the picture
 -----
 Functionals:
    Set the image sizes:
        $obj->Size_Set($array);
        This function is waiing for an array like this:
            array(
                'bigwidth'    =>1024,   //Large dimensions    
                'bigheight'   =>768,
                'mediumwidth' =>250,    //Medium dimensions
                'mediumheight'=>250,
                'smallwidth'  =>100,    //Small dimensions    
                'smallheight' =>100
            );
    Set the thumbnail options:
        Allowances:
            $obj->Thumbnail_set($allowethumbnail,$allowethumbsdir,$allowethendname,$allowemediumthumb);
            We can allowe:
                -The thumbnail,
                -Thumbnail in different dir,
                -Concat a tag end of the thumbnail's name,
                -The medium thumbnail
        Thumbnail name set:
            $obj->Thumbnail_name_set($small,$medium);
            We can change the tag en of the thumbnails name
            
        
    
*/
class ImageUploader{
    private $IMG=array('jpeg', 'jpg', 'bmp');
    private $ERROR;
    private $PARAM=array(
            'bigwidth'    =>651,
            'bigheight'   =>605,
            'mediumwidth' =>117,
            'mediumheight'=>96,
            'smallwidth'  =>117,
            'smallheight' =>96
    );
    private $DEST_NAME;                                            //The image name end of the process
    private $DIR;                                                  //The base dir where are the images
    private $THUMBS;                                               //Thumbnail dir 
    private $REPLACE=TRUE;                                         //Replace the image 
    private $AlLOWETHUMBSDIR=TRUE;                                 //Take the thumbnails in separetely dir
    private $ALLOWETHUMBNAIL=TRUE;                                 //The thumbnail making allowance
    private $ALLOWEMEDIUMTHUMB=FALSE;                              //The medium thumbnail allowance
    private $THENDNAME=FALSE;                                      //Concat the $THNAME
    private $THNAME="_th";                                         //Its appear end of the thumbnail file name if its allowed
    private $THMNAME="_thm";                                       //Its appear end of the medium thumbnail file name if its allowed
    private $WATERMARK=FALSE;                                      //Allow the watermark 
    private $WATERMARKIMAGE = 'watermark.png';                     //The watermark image path
    private $CUTBIG=FALSE;                                         //Cut the edges of big image if its bigger
    private $CUTMEDIUM=TRUE;                                       //Cut the edges of medium image if its bigger
    private $CUTSMALL=TRUE;                                        //Cut the edges of small image if its bigger
    
    Public function Cut_Set($big_cut,$medium_cut,$small_cut){
        $this->CUTBIG=$big_cut;
        $this->CUTMEDIUM=$medium_cut;
        $this->CUTSMALL=$small_cut;
    }
    Public function Watermark_set($allow,$path){
        $this->WATERMARK=$allow;                     //Set the watermark allowance
        $this->WATERMARKIMAGE=$path;                 //Set the watermark path 
    }
    Public function Thumbnail_set($allowethumbnail,$allowethumbsdir,$allowethendname,$allowemediumthumb){
        $this->ALLOWETHUMBNAIL=$allowethumbnail;     //Set the thumbnail allowance
        $this->AlLOWETHUMBSDIR=$allowethumbsdir;     //Set the thumbnail in separately dir allowance
        $this->ALLOWEMEDIUMTHUMB=$allowemediumthumb; //Set the medium thumbnail allowance
        $this->THENDNAME=$allowethendname;           //Set THNAME concat allowance
    }
    Public function Thumbnail_name_set($small,$medium){
        $this->THNAME=$small;           //The small name
        $this->THMNAME=$medium;         //The medium name    
    }
    Public function Size_set($sizes_array){
        $this->PARAM=$sizes_array;
    }
    public function __Construct($error){                    
        $this->ERROR=$error;
    }
    
    Private function Image_create($image){                         //Make a new jpeg picture 
        $created=array(
            'image' => "",
            'width' => "",
            'height'=> "" 
        );
        $created['image'] = imagecreatefromjpeg($image);
        $created['width'] = imagesx($created['image']);
        $created['height']= imagesy($created['image']);
        return $created;
    } 
    Private function Watermarker($Img){                             //The watermarker
        $watermark['image']  = imagecreatefrompng('watermark.png'); // igy nem kell ujra létrehozni
        $watermark['width']  = imagesx($watermark['image']);
        $watermark['height'] = imagesy($watermark['image']);
        $original  = $this->Image_create($Img);
        $dest_x = $original['width']  - $watermark['width']  - 10; 
        $dest_y = $original['height'] - $watermark['height'] - 10;
        imagecopy ($original['image'], $watermark['image'], $dest_x, $dest_y, 0, 0, $watermark['width'], $watermark['height']);
        $kep_kicsi=imagecreatetruecolor($original['width'], $original['height']);
        imagecopyresampled($kep_kicsi,$original['image'], 0,0,0,0, $original['width'], $original['height'], $original['width'], $original['height']);
        imagejpeg($kep_kicsi, $Img , 90);
        imagedestroy($kep_kicsi);
        imagedestroy($original['image']);
        imagedestroy($watermark['image']);
    }
    Private function Resize($file_name, $max_width, $max_height, $cut, $end="") {
        $alap = $this->Image_create($file_name);
        $width_rel = $alap['width'] / $max_width;
        $height_rel = $alap['height'] / $max_height;
        $rel = max($width_rel, $height_rel);
        $image_r_w=$alap['width']/$rel;
        $image_r_h=$alap['height']/$rel;
        $x=$y=0;
        if($cut){ // cut the edges if its allowed
            if($alap['width']>=$alap['height']){
                $x=round(($alap['width']-$alap['height'])/2);
                $y=0;
                $alap['width'] = $alap['height'];
            }else{ //if standing
                $y=round(($alap['height']-$alap['width'])/2);
                $x=0;
                $alap['height'] = $alap['width'];
            }
            $image_r_h=$max_height;
            $image_r_w=$max_width;
        }
        //echo(" ".$x."|".$y." ");
        $kep_kicsi=imagecreatetruecolor($image_r_w, $image_r_h);
        imagecopyresampled($kep_kicsi,$alap['image'], 0,0,$x,$y, $image_r_w, $image_r_h, $alap['width'], $alap['height']);    
        $IMAGEPATH=$this->DIR;                  //Make the path for the image
        if(($max_width<=$this->PARAM['mediumwidth'] || $max_width<=$this->PARAM['smallwidth']) || ($max_height<=$this->PARAM['mediumheight'] || $max_height<=$this->PARAM['smallheight'])){
            If($this->AlLOWETHUMBSDIR){
                $IMAGEPATH=$this->THUMBS."/";           //If its a thumbnail make the path from thumbs
            }    
        }
        imagejpeg($kep_kicsi, strtolower($IMAGEPATH.$this->DEST_NAME.$end.".jpg"), 90);    //Make the image
        if($this->WATERMARK && $max_width>300)  $this->Watermarker($IMAGEPATH.$this->DEST_NAME.$end.".jpg");                               //If the width bigger than 300px, watermark the image
        
    }
    Private function Image_chk($post, $name=""){
        ini_set("memory_limit","512M");
        if(!is_string($post)){                      //If is an array cjeck the tipe
            if ( $post['type'] != "image/jpeg" ){
                throw new Answer($this->ERROR['wrongext']);
                return FALSE;
            }
            $file_name=$post['name'];
        }else{                                      //If is a string explode and get the file name
            $path=explode('/', $post);
            $file_name=end($path);
        }
        $file_name=explode(".",$file_name);         //Get the file ext and check it if it isset in the $IMG var
        if(!in_array(strtolower(end($file_name)), $this->IMG)){
            throw new Answer($this->ERROR['wrongext']);
                return FALSE;
        }
        if(!$this->REPLACE){                     //If replace is FALSE, we check the file exist
            if(file_exists($this->DIR."/".$file_name[0])){
                throw new Answer($this->ERROR['exist'].": <b>".$file_name[0]."</b>");
                return FALSE;
            }
        }elseif($this->REPLACE==TRUE){                //If the replcae is TRUE we replace the file  
            if(file_exists($this->DIR."/".$file_name[0])){
                unlink($this->DIR."/".$file_name[0]);
            }
        }
            if($name==""){                          //Change the name if it's needed
                $this->DEST_NAME=$file_name[0];
            }else{
                $this->DEST_NAME=$name;
            }
            strtolower($this->DEST_NAME);           //Make the image name with lowered string
            if(!is_dir($this->DIR)) mkdir($this->DIR);            //"albums/{dir}" dir
            if($this->AlLOWETHUMBSDIR) if(!is_dir($this->THUMBS)) mkdir($this->THUMBS);
        return TRUE;    
    }
    Public function Dir_path($dir, $thumbdir="thumbs"){
        $this->DIR=$dir;                             //Set upload dir
        $this->THUMBS=$this->DIR.$thumbdir;          //Set thumbnails dir
    }
    Public function Image_uploader($img,$replace=TRUE,$newname=""){
        $this->REPLACE=$replace;                     //Set image replacement   
        $tmp=$img;                                   //If the image just a link   
        if(is_array($img)) $tmp=$img['tmp_name'];    //If the image came from a uploadform and is array
        if($this->Image_chk($img,$newname)){
            $this->Resize($tmp, $this->PARAM['bigwidth'],   $this->PARAM['bigheight'], $this->CUTBIG);
            if($this->ALLOWETHUMBNAIL){
                $end="";
                if($this->THENDNAME) $end=$this->THNAME;
                $this->Resize($tmp, $this->PARAM['mediumwidth'], $this->PARAM['mediumheight'],$this->CUTMEDIUM,$end);
            }
            if($this->ALLOWEMEDIUMTHUMB){
                $end="";
                if($this->THENDNAME) $end=$this->THMNAME;
                $this->Resize($tmp, $this->PARAM['smallwidth'], $this->PARAM['smallheight'],$this->CUTSMALL,$end);
            }
            $this->DIR=$this->DEST_NAME=$this->THUMBS="";
            return TRUE;
        }
        return FALSE;
    }
}

?>