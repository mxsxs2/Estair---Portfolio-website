<?php
include_once'config.php';
include_once'lang/'.$_SESSION['lang'].'.php';
?>
    <div id="albumbar">
        <ul id="albummenu">
        <?php
            $albums=$mysql->select('`name_'.$_SESSION['lang'].'`,`dir` ','album',0,'`active`=1 AND `photos`>0');
            if($albums!=FALSE){
                while($album=mysql_fetch_array($albums)){
                    if(is_dir('../imgs/'.$album['dir'])){
                        if(count(scandir('../imgs/'.$album['dir']))>2){
                            echo('<li id="album?a='.$album['dir'].'" class="albumbutton">'.strtoupper($album['name_'.$_SESSION['lang']]).'</li>');
                        }
                    }
                }
            }
        ?>
        </ul>
    </div>
<?php
if(!isset($_GET['a'])){
    $arr=$mysql->one_row("`dir`",'album','`active`=1 AND `photos`>0');
    $_GET['a']=$arr['dir'];
}
if(isset($_GET['a'])){
    $album=strtolower($SECURE->ell($_GET['a']));
    $arr=$mysql->one_row("`id`",'album',"`active`=1 AND `photos`>0 AND `dir`='".$album."'");
    if(!is_array($arr)) die;
    if($album!=false && is_dir('../imgs/'.$album)){
        $images=$mysql->select('`id`,`filename`, `name_'.$_SESSION['lang'].'`, `desc_'.$_SESSION['lang'].'`','photos',0,"`album`='".$album."'");
        if($images!=FALSE){
            while($photo=mysql_fetch_array($images)){
            ?>
                <div class="photo_container" id="c_<?php echo($photo['id']); ?>">
                    <div class="photo_first">
                        <input type="hidden" value="<?php echo($album."_!_".$photo['filename'].".jpg"); ?>" class="small_hidden" id="h_<?php echo($photo['id']); ?>"/>
                        <img src="<?php echo("imgs/".$album."/thumbs/".$photo['filename'].".jpg"); ?>" class="small" id="<?php echo($photo['id']); ?>" alt="<?php echo($photo['name_'.$_SESSION['lang']]); ?>" />
                    </div>
                    <div class="thumb_cover" id="t_<?php echo($photo['id']); ?>">
                        <span class="photo_title"><?php echo($photo['name_'.$_SESSION['lang']]); ?></span><br />
                        <span class="photo_desc"><?php echo($photo['desc_'.$_SESSION['lang']]); ?></span>
                    </div>
                </div>    
            <?php
            }
            ?>
            <?php
        }
    }
    
}
?>