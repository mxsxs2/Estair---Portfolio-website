<?php
include_once'../php/config.php';
include_once'../php/lang/ad_'.$_SESSION['lang'].'.php';
$SECURE->logined(1);
if(isset($_POST['t'])){
    function upfile($path,$jsselector,$file,$name,$lang){ //image upload function
        include_once('uploader.php');
            $upl= new ImageUploader($lang);
            try{
                $upl->Dir_path($path);
                $upl->Thumbnail_set(FALSE,FALSE,FALSE,FALSE);
                $upl->Image_uploader($file,true,$name);
                ?>
                <script type="text/javascript">
                    <!--
                    parent.$(document).ready(function(){
                       parent.$("<?php echo($jsselector); ?>").trigger('click');
                       parent.$("<?php echo($jsselector); ?>").trigger('click');
                       parent.$.Boxmini_close(); 
                    });
                    -->
                </script>
                <?php
            }catch(Answer $e){
                if($e->getMessage()==1){
                    echo($e->getMessage());
                }
            }
    }
    //-------------------------------------------------------------home
    if($_POST['t']=="h"){ // delete a picture from home 
        if(isset($_POST['del']) && $_POST['del']!=""){
            if(is_file($_POST['del'])){
                unlink($_POST['del']);
                echo(1);
            }else{
                echo($LANG['error']['url']);
            }
        }
    }
    if($_POST['t']=="ahp"){ //upload a picture to home
        if(isset($_FILES['upload_file']) && !empty($_FILES)){
            include_once('uploader.php');
            $upl= new ImageUploader($LANG['op']);
            try{
                $upl->Size_Set(array(
                    'bigwidth'    =>661,   //Large dimensions    
                    'bigheight'   =>665
                ));
                $upl->Cut_Set(TRUE,TRUE,TRUE);
                $upl->Dir_path('../imgs/home/');
                $upl->Thumbnail_set(FALSE,FALSE,FALSE,FALSE);
                $upl->Image_uploader($_FILES['upload_file'],true,md5($_FILES['upload_file']['name']));
                ?>
                <script type="text/javascript">
                    <!--
                    parent.$(document).ready(function(){
                       parent.$(".cat_open[id='home']").trigger('click');
                       parent.$(".cat_open[id='home']").trigger('click');
                       parent.$.Boxmini_close(); 
                    });
                    -->
                </script>
                <?php
                return true;
            }catch(Answer $e){
                if($e->getMessage()==1){
                    echo($e->getMessage());
                    return false;
                }
            }
        }
    }
    //---------------------------------------------------------album
    if($_POST['t']=="abpc" && isset($_POST['lang'],$_POST['i'])){ // image desc/name change
            $lngs=explode("!",$SECURE->ell($_POST['lang']));
            $set="`id`=`id`";
            foreach($lngs as $lng ){
                if($lng!=""){
                    if($SECURE->ell($_POST['name_'.$lng]) && $SECURE->ell($_POST['desc_'.$lng])){
                        $set.=", `name_".$lng."`='".$SECURE->ell($_POST['name_'.$lng])."', `desc_".$lng."`='".$SECURE->ell($_POST['desc_'.$lng])."'";
                    }
                }
            }
            if($_POST['t']=="abpc" && $SECURE->ell_n($_POST['i'])){
                $up=$mysql->update('photos',$set,1,"`id`=".$SECURE->ell_n($_POST['i']));
                if($up!=FALSE){
                    echo(1);
                }
            }
        }
     if($_POST['t']=="abpd" && isset($_POST['i']) && is_numeric($_POST['i'])){ //Picture delete
        $id=$SECURE->ell_n($_POST['i']);
        $img=$mysql->one_row('`album`,`filename`','photos','`id`='.$id);
        if($img!=FALSE){
                    $update=$mysql->update('album','`photos`=IF(`photos`>0,`photos`-1,`photos`)',1,"`dir`='".$img['album']."'");
                    if($update!=FALSE){
                        $del=$mysql->delete('photos',1,'`id`='.$id);
                        if($del!=FALSE){
                            unlink("../imgs/".$img['album']."/".$img['filename'].".jpg");
                            unlink("../imgs/".$img['album']."/thumbs/".$img['filename'].".jpg");
                            echo(1);
                        }
                    }
        }
     }
     if($_POST['t']=="aap" && isset($_FILES['upload_file'], $_POST['alb']) && !empty($_FILES)){ // image upload
            $album=$SECURE->ell($_POST['alb']);
            $alb_id=$mysql->one_row('`id`','album', "`dir`='".$album."'");
            if($alb_id!=FALSE){
                $name=md5($_FILES['upload_file']['name']."_".time());
                $cells="`album`,`filename`";
                $values="'".$album."', '".$name."'";
                unset($_POST['alb'],$_POST['t']);
                $keys=array_keys($_POST);
                foreach($keys as $key){
                    $cells.=", `".$key."`";
                    $values.=", '".$SECURE->ell($_POST[$key])."'";
                }
               include_once('uploader.php');
               $upl= new ImageUploader($LANG['op']);
               try{
                $upl->Cut_Set(FALSE,TRUE,TRUE);
                $upl->Thumbnail_set(TRUE,TRUE,FALSE,FALSE);
                $upl->Dir_path('../imgs/'.$album.'/');
                $upl->Size_Set(array(
                    'bigwidth'    =>1024,   //Large dimensions    
                    'bigheight'   =>1003,
                    'mediumwidth' =>137,    //Medium dimensions
                    'mediumheight'=>116,
                    'smallwidth'  =>137,    //Small dimensions    
                    'smallheight' =>116
                ));
                if($upl->Image_uploader($_FILES['upload_file'],true,$name)){
                    $ins=$mysql->insert('photos',$cells,$values);
                    $update=$mysql->update('album','`photos`=`photos`+1',1,"`id`='".$alb_id['id']."'");
                    ?>
                    <script type="text/javascript">
                        <!--
                        parent.$(document).ready(function(){
                            parent.$("<?php echo(".albumbutton[id='".$album."']"); ?>").trigger('click');
                            parent.$.Boxmini_close(); 
                        });
                        -->
                    </script>
                    <?php
               }else{
                echo('uploader');
               }
               }catch(Answer $e){
                    echo($e->getMessage());
               }
            }else{
                echo($LANG['login']['error']);
            }
            die;        
    }
    if($_POST['t']=="asc" && isset($_POST['i']) && is_numeric($_POST['i'])){ //Album status change
        $upd=$mysql->update('album','`active`= IF (`active`=1,0,1)',1,"`id`=".$_POST['i']);
        if($upd!=FALSE){
            echo(1);
        }
    }
    if($_POST['t']=='anew'){ // new album
        $langs=lang();
        $i=sizeof($langs);
        if(isset($_POST['en'])){
            $dir=strtolower(str_replace(" ","",$SECURE->ell($_POST['en'])));
        }else{
            $dir=substr(sha1(time()),0,5);
        }
        $cells="`dir`";
        $values="'".$dir."'";
        for($c=0;$c<$i; $c++){
            if(isset($_POST[$langs[$c]])){
                $post=$SECURE->ell($_POST[$langs[$c]]);
                if($post!=FALSE && $post!=$langs[$c]){
                   $cells.=",`name_".$langs[$c]."`";
                   $values.=",'".$post."'"; 
                }
            }
        }
        $ins=$mysql->insert('album',$cells,$values);
        if($ins!=FALSE){
            if(!is_dir("../imgs/".$dir)){
                if(mkdir("../imgs/".$dir)){
                    if(mkdir("../imgs/".$dir."/thumbs")){
                        echo(1);
                    }
                }
            }
        }
    }
    if($_POST['t']=="are" && isset($_POST['i']) && is_numeric($_POST['i'])){ //Album rename
        $id=$SECURE->ell_n($_POST['i']);
        $langs=lang();
        $i=sizeof($langs);
        $set="`id`=`id`";
        for($c=0;$c<$i; $c++){
            if(isset($_POST[$langs[$c]])){
                $post=$SECURE->ell($_POST[$langs[$c]]);
                if($post!=FALSE){
                   $set.=", `name_".$langs[$c]."`='".$post."'"; 
                }
            }
        }
        if($set!="1=1"){
            $upd=$mysql->update("album",$set,1,"`id`=".$id);
            if($upd!=FALSE){
                echo(1);
            }
        }
    }
    if($_POST['t']=="adel" && isset($_POST['i']) && is_numeric($_POST['i'])){ //Album delete
        $id=$SECURE->ell($_POST['i']);
        if($id!=FALSE){
            $alb=$mysql->one_row("`dir`","album","`id`=".$id);
            if(is_array($alb) && $alb['dir']!=""){
                $dirdel=new delete();
                $dirPath='../imgs/'.$alb['dir'];
                if($dirdel->deleteDir($dirPath)){
                    $picsdel=$mysql->delete("photos",0,"`album`='".$alb['dir']."'");
                        if($picsdel!=FALSE){
                           $albdel=$mysql->delete("album",1,"`id`=".$id);
                           if($albdel!=FALSE){
                            echo(1);
                           }
                        }
                }
            }
        }
    }
    //-----------------------------------------------------------about
    if($_POST['t']=="abp"){ //change the picture of about
        if(isset($_FILES['upload_file']) && !empty($_FILES)){
            include_once('uploader.php');
            $upl= new ImageUploader($LANG['op']);
            try{
                $upl->Cut_Set(TRUE,TRUE,TRUE);
                $upl->Dir_path('../imgs/');
                $upl->Thumbnail_set(FALSE,FALSE,FALSE,FALSE);
                $upl->Size_Set(array('bigwidth'    =>320,'bigheight'   =>625));
                $upl->Image_uploader($_FILES['upload_file'],true,"about");
                ?>
                <script type="text/javascript">
                    <!--
                    parent.$(document).ready(function(){
                       parent.$(".cat_open[id='about']").trigger('click');
                       parent.$(".cat_open[id='about']").trigger('click');
                       parent.$.Boxmini_close(); 
                    });
                    -->
                </script>
                <?php
            }catch(Answer $e){
                    echo($e->getMessage());
            }
        }
    }
    if($_POST['t']=="abt" && isset($_POST['dat'])){ #About text change
        $up=$mysql->update("about",'`about_'.$_SESSION['lang'].'`="'.mysql_real_escape_string($_POST['dat']).'"',1);
    }
    if($_POST['t']=="c"){ //contact
        if(isset($_POST['phone']) && is_numeric($_POST['phone'])){
            if($mysql->update('contact','`phone`='.$SECURE->ell_n($_POST['phone']).'',1)){
                echo($SECURE->ell_n($_POST['phone']));
            }
        }
        if(isset($_POST['mail']) && $SECURE->ell($_POST['mail'])){
             if(preg_match('/^([a-z0-9]+([_\.\-]{1}[a-z0-9]+)*){1}([@]){1}([a-z0-9]+([_\-]{1}[a-z0-9]+)*)+(([\.]{1}[a-z]{2,6}){0,3}){1}$/i', $_POST["mail"])){
                if($mysql->update('contact','`email`="'.$SECURE->ell($_POST['mail']).'"',1)){
                      echo($SECURE->ell($_POST['mail']));
                }
             }
            
        }
    }
}


?>