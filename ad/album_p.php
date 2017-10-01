<?php
include_once'../config.php';
include_once('../lang/langset.php');
if(@isset($_POST[$_SESSION['admin']['token']]) && $SECURE->logined(0)){   
    $i=0;
	if($_POST[$_SESSION['admin']['token']]=="") $i++;
	if($_POST[$_SESSION['admin']['token']]!=$_SESSION['admin']['input']) $i++;
    if($i!=0){
        die;
    }
    //Album creation----------------------------------------------
    if(isset($_POST['name'], $_POST['path'], $_POST['a_type'])){
        if(!$SECURE->ell($_POST['a_name'])){
            echo($LANG["error"]['albumname']);
            die;
        }
        if(!$SECURE->ell_n($_POST['a_type'])){
            die;
        }
        if($mysql->one_row('`id`','album',"`name`='".$SECURE->ell($_POST['name'])."'")){
            echo($LANG["error"]['existalbum']);
            die;
        }
        $dir='../albums/'.$_POST['path'];
        if(is_dir($dir)){
            try{
                include_once('uploader.php');
                $uploader= new ImageUploader($LANG['error']);
                $i==0;
                foreach(glob($dir."/*.{gif,jpg,png,jpeg,JPG,JPEG,PNG,GIF}", GLOB_BRACE) as $img_url){
                    set_time_limit("15");
                    $uploader->Dir_path($dir);
                    if($uploader->Image_uploader($img_url,2)){
                    $i++;
                    }
                    $last=$img_url;
                }
                if($i>0){
                    $name=explode('/', $last);
                    $file_name=end($name);
                    if($mysql->insert('album',"`name`,`type`,`dir`,`cover`,`count`","'".$SECURE->ell($_POST['a_name'])."',".$SECURE->ell_n($_POST['a_type']).",'".$dir."','".$file_name."',".$i)){
                        echo($LANG['global']['ready']);
                    }
                }
            }catch(Answer $e){
                echo($e->getMessage());
            }
        }
    }
    //Book creation---------------------------------
    if(isset($_POST['name'], $_FILES['bookcover'], $_POST['desc'], $_POST['isbn'], $_POST['link'])){
        foreach($_POST as $key=>$val){
            if(!$SECURE->ell($val)){
                echo($LANG['error']['value']);
                die;
            } 
        }
        
        $ch=$mysql->one_row('`id`','book',"`name`='".$SECURE->ell($_POST['name'])."'");
        if($ch!=FALSE){
            echo($LANG['error']['exists2']);
            die;
        }
        try{
            include_once('uploader.php');
            $uploader= new ImageUploader($LANG['error']);
            $uploader->Dir_path('../bookcovers');
            $uploader->Thumbnail_set(FALSE,FALSE,FALSE,FALSE);
         
            if($uploader->Image_uploader($_FILES['bookcover'])){
                $path="../bookcovers/".$_FILES['bookcover']['name'];
                if($mysql->insert('book',"`name`,`cover`,`link`,`description`,`isbn`","'".$SECURE->ell($_POST['name'])."','".$path."','".$SECURE->ell($_POST['link'])."','".$SECURE->ell($_POST['desc'])."','".$SECURE->ell($_POST['isbn'])."'")){
                    echo($LANG['global']['ready']);
                }
            }
        }catch(Answer $e){
             echo($e->getMessage());
        }
    }
}
?>