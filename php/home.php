<?php
$dir ="../imgs/home";
    if (is_dir($dir) && is_array(glob($dir."/*.{jpg,JPEG}", GLOB_BRACE))){
        $i=1;
        foreach(glob($dir."/*.{jpg,JPEG}", GLOB_BRACE) as $img_url){
            $img_url=substr($img_url,3);
            if($i==1) $first=$img_url;
                echo('<input type="hidden" value="'.$img_url.'" id="i'.$i.'"/>');
                    $IMGS[$i]=$i;
                    $i++;    
        }
        $all=sizeof($IMGS);     
    }else{
        $all=0;
    }
 ?>
<div id="home_pic_cont">
    <div>
    <?php if(isset($first)) {?>
        <img src="<?php echo($first); ?>" id="homepic" alt=""/>
    <?php } ?>
    </div>
    <div id="home_bar">
        <span id="home_control"><img src="css/img/pause.png" height="8px" alt="" class="home_button"/></span>
        <span id="home_counter">1</span>/<?php echo($all); ?>
    </div>
</div>
<div id="load">
</div>