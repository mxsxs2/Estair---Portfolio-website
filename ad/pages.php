<?php
include_once'../php/config.php';
include_once'../php/lang/ad_'.$_SESSION['lang'].'.php';
$SECURE->logined(1);
if(isset($_GET["cat"])){
    if($_GET['cat']=="home"){ //------------------------------------home page settings
        ?><div class="ahome_s_cont" id="addnewhomepic"></div><?php
        $dir ="../imgs/home";
        if (is_dir($dir) && is_array(glob($dir."/*.{jpg,JPEG}", GLOB_BRACE))){
            $i=1;
            foreach(glob($dir."/*.{jpg,JPEG}", GLOB_BRACE) as $img_url){
                    echo('<div class="ahome_s_cont" id="c_'.$i.'">
                            <div class="aimg_cont">
                                <img src="'.$img_url.'" id="i_'.$i.'" class="ad_small2" alt=""/>
                            </div>
                            <div class="ahome_del">
                                <img src="../css/img/delete.png" alt="'.$LANG['op']['del'].'" width="50px"/>
                            </div>
                           </div>');
                        $i++;    
            }   
        }
    }
    if($_GET['cat']=="album"){//-------------------------album settings
        ?>
    <div id="albumbar2">
        <ul id="adalbummenu">
        <?php
            echo('<li id="" class="newalbum">'.strtoupper($LANG['op']['newalb']).'</li>'); 
            $albums=$mysql->select('`name_'.$_SESSION['lang'].'`,`dir`,`photos`','album',0);
            if($albums!=FALSE){
                while($album=mysql_fetch_array($albums)){
                            echo('<li id="'.$album['dir'].'" class="albumbutton">'.strtoupper($album['name_'.$_SESSION['lang']]).'('.$album['photos'].')</li>');
                    
                }
                ?>
        </ul>
    </div>
                    <div id="albumscont">
                    </div>
                    <script type="text/javascript">
                        $(".albumbutton").click(function(){
                            self=this;
                            $(".albumbutton").removeClass("current");
                            $(self).addClass("current");
                                $.ajax({
                                    url : "app.php",
                                    type: "GET",
                                    data: "t=oa&alb="+$(self).attr('id'),
                                    dataType: 'html',
                                    beforeSend: function(){
                                        $('#albumscont').html('<p class="centered"><img src="../css/img/load.gif" alt="" border="0" width="16px" style="margin-top: 5px"></p>');
                                    },
                                    success: function(html){
                                        if(html!=0){
                                            $('#albumscont').filter(function(){
                                                $('#albumscont').html(html);
                                                theight=$('#cat_tree').height();
                                                if(theight>665){
                                                    $('#bg').css('height', theight);
                                                }else{
                                                    $('#bg').css('height', '665px');
                                                }
                                            });
                                            $("img.small").filter(function(){
                                                id=$(this).attr('id');
                                                value=$('#h_'+id).val();
                                                value=value.split("_!_");
                                                src="../imgs/"+value[0]+"/thumbs/"+value[1];
                                                $("#"+id).attr('src',src); 
                                            });     
                                        }
                                    }
                                }); 
                        });
                    </script>
                <?php
            }
        ?>
<?php       
    }
    if($_GET['cat']=="about"){//-------------------------about settings
        $ab=$mysql->one_row('`about_'.$_SESSION['lang'].'`','about');
        ?>
            <link href="jqte/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="jqte/jquery-te-1.4.0.js"></script>
            <div class="adaboutpic">
                <p class="centered">    
                    <input type="button" id="aboutchangepic" value="<?php echo($LANG['op']['change']); ?>"/><br />
                    <img src="../imgs/about.jpg" alt="" height="515px" id="adabpic"/>
                </p>
            </div>
            <div class="adabouttext">
                <p class="centered">
                    <input type="button" id="aboutedittext" class="0" value="<?php echo($LANG['op']['edit']); ?>"/>
                </p>
                <div id="abouttextbox">
                    <?php echo($ab['about_'.$_SESSION['lang']]); ?>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#aboutchangepic").click(function(){
                        $(this).Boxmini("app.php","t=abp");
                    });
                    $("#aboutedittext").bind("click",function(){
                        but=$(this);
                        if(but.attr("class")==0){
                            $("#abouttextbox").jqte({"status" : true,});
                                but.val("<?php echo($LANG['op']['save']);?>")
                                   .attr("class","1");
                        }else{
                            $.ajax({
                                    url : "sets.php",
                                    type: "POST",
                                    data: "t=abt&dat="+$(".jqte_editor").html(),
                                    success: function(html){
                                            $("#abouttextbox").jqte({"status" : false,});    
                                            but.val("<?php echo($LANG['op']['edit']);?>")
                                               .attr("class","0"); 
                                    }
                                });
                        }        
                    });
                });  
            </script>
        <?php
    }
    if($_GET['cat']=="contact"){//-----------------------contact settings
        $c=$mysql->one_row('`phone`,`email`','contact');
        ?>
                <div>
                    <table id="conttable">
                        <tr>
                            <td  style="width: 100px;"><?php echo($LANG['op']['mail']); ?>:</td>
                            <td style="width: 400px;"><input type="text" class="input2" value="<?php echo($c['email']); ?>" id="m" name="mail"/></td>
                            <td class="feedb" id="mf"></td>
                        </tr>
                        <tr>
                           <td  style="width: 100px;"><?php echo($LANG['op']['phone']); ?>:</td>
                           <td  style="width: 400px;"><input type="text" class="input2" value="<?php echo($c['phone']); ?>" id="p" name="phone"/></td>
                           <td class="feedb" id="pf"></td>
                        </tr> 
                    </table>
                </div>
                
                <script type="text/javascript">
                    <!--
	                   $(document).ready(function(){
	                      $('input').click(function(){
              	             $("#"+$(this).attr('id')+"f").addClass('save');              
                          });
                          $('td.feedb').bind('click', function(){
                            self=this;
                                w=$(this).attr('id').slice(0,1);
                                $.ajax({
                                    url : "sets.php",
                                    type: "POST",
                                    data: "t=c&"+$("#"+w).attr('name')+"="+$("#"+w).val(),
                                    dataType: 'html',
                                    beforeSend: function(){
                                        $(self).html('<img src="../css/img/load.gif" alt="" border="0" width="16px" style="margin-top: 5px">');
                                    },
                                    success: function(html){
                                        if(html!=0){
                                            $(self).addClass('done')
                                                   .removeClass('save')
                                                   .empty();
                                            $('#'+w).val(html);     
                                        }
                                    }
                                });  
                          });
	                   });
                    -->
                </script>
        <?php
    }
}
?>