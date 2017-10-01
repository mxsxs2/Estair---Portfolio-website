<?php
include_once'../php/config.php';
include_once'../php/lang/ad_'.$_SESSION['lang'].'.php';
$SECURE->logined(1);
if(isset($_GET['t'])){
    //------------------------------------------------------------home
    if($_GET['t']=="h"){ //---------------------------------home pic delete
        if(isset($_GET['del'], $_GET['i']) && $_GET['del']!="" && $_GET['i']!=""){
            ?><div style="float: left; margin: 5px;">
                <img src="<?php echo($_GET['del']); ?>" alt="" height="60px" id="delimg"/>
              </div>
              <div style="float: left;">
                <?php echo($LANG['op']['idel']."<br>
                <input type='button' value='".$LANG['op']['del']."' id='delete' class='".$_GET['i']." button'>
                <input type='button' value='".$LANG['op']['cancel']."' id='cancel' class='button'>
              </div>
                <p id='back'></p>");
        }
    }
    if($_GET['t']=="ahp" || $_GET['t']=="abp" || $_GET['t']=="aap"){ //--------------------------addnew home/about/album image
        ?><form enctype="multipart/form-data" method="POST" id="adpicup" name="form" action="sets.php" target="hf" >
            <input type="file" name="upload_file" id="u_file" class="input2"/><br />
            <input type="hidden" name="t" value="<?php echo($_GET['t']); ?>"/>
            <?php if($_GET['t']!="aap"){
                ?><input type="button" id="p_upload" value="<?php echo($LANG['op']['upload']); ?>" class="button" />
          </form>
          <?php } ?>
        <?php
    }
    //------------------------------------------------------------album
    if($_GET['t']=="oa" && isset($_GET['alb'])){//-----------------------------open album
        $album=strtolower($SECURE->ell($_GET['alb']));
        $alb=$mysql->one_row('*','album',"`dir`='".$album."'");
        if(is_array($alb)){
            ?>
            <div id="alb_options_cont">
                <?php echo($LANG['g']['album']." ".$LANG['op']['status'])?>:
                <select id="status" class="<?php echo($alb['id']); ?>">
                    <?php
                        if($alb['active']==1){
                           echo('<option value="1" selected>'.$LANG['op']['active'].'</option>
                                 <option value="0">'.$LANG['op']['inactive'].'</option>'); 
                        }else{
                           echo('<option value="0" selected>'.$LANG['op']['inactive'].'</option>
                                 <option value="1">'.$LANG['op']['active'].'</option>');
                        }
                    ?>
                </select>
                <input type="button" class="albrename" id="<?php echo($alb['id']); ?>" value="<?php echo($LANG['op']['rename']); ?>"/>
                <input type="button" class="albdelete" id="<?php echo($alb['id']); ?>" value="<?php echo($LANG['op']['del']); ?>"/>
            </div>
             <div id="addphoto" class="<?php echo($album); ?>">   
             </div>
             <script type="text/javascript">
                $(document).ready(function(){
                    $("div#addphoto").click(function(){
                        $(this).Boxmini("app.php","t=aap&alb="+$(this).attr('class'));
                    });
                });
             </script>
             <?php
            $images=$mysql->select('`id`,`filename`, `name_'.$_SESSION['lang'].'`, `desc_'.$_SESSION['lang'].'`','photos',0,"`album`='".$alb['dir']."'");
            if($images!=FALSE){
                while($photo=mysql_fetch_array($images)){
                ?>
                <div class="photo_container" id="c_<?php echo($photo['id']); ?>">
                    <div class="photo_first">
                        <input type="hidden" value="<?php echo($alb['dir']."_!_".$photo['filename'].".jpg"); ?>" class="small_hidden" id="h_<?php echo($photo['id']); ?>"/>
                        <img src="about:blank" class="small" id="<?php echo($photo['id']); ?>" alt="<?php echo($photo['name_'.$_SESSION['lang']]); ?>" />
                    </div>
                    <div class="thumb_cover" id="t_<?php echo($photo['id']); ?>">
                        <span class="photo_title"><?php echo($photo['name_'.$_SESSION['lang']]); ?></span><br />
                        <span class="photo_desc"><?php echo($photo['desc_'.$_SESSION['lang']]); ?></span>
                    </div>
                </div>    
                <?php
                }
                ?>
                <script type="text/javascript">
                $(document).ready(function(){
                    $("div.photo_container").bind('click', function(){
                                                $(this).Boxmini("app.php","t=apc&p="+$(this).attr('id'));
                                            });
                   
                });
                </script>
                <?php
            }
        }
    
    }
    
    
    if(($_GET['t']=="apc" && isset($_GET['p']) && $SECURE->ell($_GET['p'])) || ($_GET['t']=="aap" && isset($_GET['alb']))){ //--------------change image desc/name
        $langs=lang(); //get the languages from db ->function.php
        if($_GET['t']=="apc"){
            $id=explode("_",$SECURE->ell($_GET['p']));
            $pic=$mysql->one_row('*, `album`','photos',"id='".$SECURE->ell_n($id['1'])."'");
            $button="picchange";
        }else if($_GET['t']=="aap"){
            $i=sizeof($langs);
            for($c=0; $c<$i; $c++){
                $pic['name_'.$langs[$c]]=$LANG['g']['label'].' '.$langs[$c];
                $pic['desc_'.$langs[$c]]=$LANG['g']['desc'].' '.$langs[$c];
            }
            $id[1]=0;
            $pic['album']=$SECURE->ell($_GET['alb']);
            $button="p_upload";
        }
        if($pic!=FALSE){
            $op="";
            if($_GET['t']=="apc"){
                echo('<img src="../imgs/'.$pic['album'].'/thumbs/'.$pic['filename'].'.jpg" class="alb_small"/>');
            }
                echo('<div id="apctextcont">');   
            foreach($langs as $l){
                if($l!=""){
                    if($l==$_SESSION['lang']){
                        $sel='selected';
                        $disp='';
                    }else{
                        $sel="";
                        $disp='style="display: none"';
                    }
                    $op.='<option value="'.$l.'" class="io" '.$sel.'>'.$l.'</option>';
                    echo('<fieldset id="'.$l.'" '.$disp.' class="descs">    
                            <input type="text"   value="'.$pic['name_'.$l].'" name="name_'.$l.'" id="name_'.$l.'"class="input2"/><br>
                            <input type="text"   value="'.$pic['desc_'.$l].'" name="desc_'.$l.'" id="desc_'.$l.'" class="input2"/>
                           </fieldset>');
                }
            }
            echo($LANG['g']['language'].':     
                        <select id="langsel"> 
                            '.$op.'
                        <select/> 
                        <input type="button" value="'.$LANG['op']['save'].'"  id="'.$button.'" class="'.$id[1].' '.$pic['album'].' save"/>');
            if($_GET['t']=="apc"){
                echo('<input type="button" value="'.$LANG['op']['del'].'"   id="picdelete" class="'.$id[1].'"/>');
            }
            echo('</div>');
            if($_GET['t']=="aap"){
                echo('<input type="hidden" name="alb" value="'.$pic['album'].'"/><form/>');
            }
        }
    }
    if($_GET['t']=="are" || $_GET['t']=="anew"){ //------------album rename/new album 
        if($_GET['t']=="are"  && isset($_GET['i']) && is_numeric($_GET['i'])){
          $alb=$mysql->one_row('*','album','`id`='.$SECURE->ell_n($_GET['i']));
          if(!is_array($alb)){
            echo($LANG['login']['alert']);
          }
        }else{
            $_GET['i']="";
        }
        
            $langs=lang(); //-------------langages->function.php
            $i=sizeof($langs);
            echo($LANG['g']['albname']."<br>");
            for($c=0; $c<$i; $c++){
                if($_GET['t']=="anew"){
                    $alb['name_'.$langs[$c]]=$langs[$c];
                }
                echo($langs[$c].' <input type="text" id="'.$langs[$c].'" class="albname input2" value="'.$alb['name_'.$langs[$c]].'" /><br>');
            }
            echo('<input type="button" value="'.$LANG['op']['save'].'"  id="'.$_GET['i'].'" class="albsave button"/>');
    }
    if($_GET['t']=="adel" && isset($_GET['i']) && is_numeric($_GET['i'])){//-------------album delete
        $alb=$mysql->one_row('*','album','`id`='.$SECURE->ell_n($_GET['i']));
        if(is_array($alb)){
            echo($LANG['op']['albdelete'].": ".$alb['name_'.$_SESSION['lang']].'<br>
                 <input type="button" id="'.$_GET['i'].'" class="adelcon button" value="'.$LANG['op']['del'].'" />');
        }
    }
}
?>
<script type="text/javascript">
<!--
_ajax = function(param, succes_function/*has to be function*/){
    $.ajax({
            url : "sets.php",
            type: "POST",
            data: param,
            dataType: 'html',
            beforeSend: function(){
                box.Container.html('<img src="../css/img/load.gif" alt="" border="0">');
            },
            success: function(html){
                succes_function(html);
                if(html==1){
                    $.Boxmini_close();
                }else{
                    box.Container.html(html);
                }
            }
        });  
}
	$(document).ready(function(){
	   $("#langsel").change(function(){
	      $(".descs").hide();
          $("#"+$(this).val()).show();
	   });
	   $("#picchange").click(function(){
	       clas=$(this).attr("class").split(" ");
           if($("#picname").val()!="" && $("#picdesc").val()!=""){
                params="t=abpc&i="+clas[0];
                lng="&lang=";
                $("option[class='io']").each(function(){
                    id=$(this).attr('value');
                    lng=lng+"!"+id;
                    params=params+"&name_"+id+"="+$("#name_"+id).val() //name
                    params=params+"&desc_"+id+"="+$("#desc_"+id).val() //desc
                });
                _ajax(params+lng,function(){
                    $("#"+clas[1]).trigger('click');
                });
           }
            
	   });
       $("#picdelete").click(function(){
	       clas=$(this).attr("class");
           _ajax("t=abpd&i="+clas,function(){
               $("#c_"+clas).toggle();
            });
	   });
        $("#p_upload").click(function(){
	       if($("#u_file").val()!=""){
	           $('form').submit();  
	           box.Container.html('<img src="../css/img/load.gif" alt="" border="0">');
           }
        });
       $("#delete").click(function(){
	     src=$("#delimg").attr("src");
         id=$(this).attr("class").split(" ");
         _ajax("t=h&del="+src, function(){
            $("#"+id[0]).toggle();
         });
	   });
	   $("#cancel").on("click", function(){
        $.Boxmini_close();
       });
       $("#status").change(function(){
            $('body').css('cursor', 'wait');
            _ajax("t=asc&i="+$(this).attr("class"),function(){
                $('body').css('cursor', 'auto');
            });
       });
       $(".albrename").click(function(){
            $(this).Boxmini("app.php","t=are&i="+$(this).attr("id"));
       });
       $(".albsave").click(function(){
            param="t=anew";
            id=$(this).attr("id");
            if(id!="" && id!="undefined"){
                param="t=are&i="+id;
            }
            $(".albname").filter(function(){
               inp=$(this);
               if(inp.val()!=""){
                    param=param+"&"+inp.attr('id')+"="+inp.val();
               } 
            });
            _ajax(param,function(){
                $(".cat_open[id='album']").trigger('click');
                $(".cat_open[id='album']").trigger('click');
            });
       });
       $(".albdelete").click(function(){
            $(this).Boxmini("app.php","t=adel&i="+$(this).attr("id"));
       });
       $(".adelcon").click(function(){
           _ajax("t=adel&i="+$(this).attr("id"),function(){
                $(".cat_open[id='album']").trigger('click');
                $(".cat_open[id='album']").trigger('click');
           }); 
       });
	});
-->
</script>