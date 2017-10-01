<?php 
include_once('php/lang/langset.php');
include_once('php/config.php'); ?>
<!DOCTYPE html> 
<html>
<head>
<link rel="icon" href="css/img/earth.png" type="image/x-icon" />
<meta charset="utf-8" />
<title>Estair makeup</title>
<!--[if IE 6]><base href="<?php echo($CONFIGVAR['web'].$CONFIGVAR['SELFDIR']);?>/"></base><![endif]-->
<!--[if !IE 6]><!--><base href="<?php echo($CONFIGVAR['web'].$CONFIGVAR['SELFDIR']);?>/" /><!--<![endif]-->
<link href="css/index.css" rel="stylesheet" type="text/css" />
<link href="css/boxmini.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery-coloranim.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/home.js"></script>
<script type="text/javascript" src="js/box.js"></script>
</head>
<body>
 <header id="frame">
    <section id="leftside">
        <div id="namecont">
            <p id="name"><?php echo($LANG['index']['name']); ?></p>
            <p id="label"><?php echo($LANG['index']['label']); ?></p>
            <p id="albumtext"></p>
        </div>
    </section>
    <section id="content">
    </section>
    <nav id="menubar">
        <ul id="menucontainer">     
            <?php 
            $albums=$mysql->select('`id`, `name_'.$_SESSION['lang'].'`,`dir` ','album',0,'`active`=TRUE && `photos`>0');
            if($albums!=FALSE){
                while($album=mysql_fetch_array($albums)){
                    if(is_dir('imgs/'.strtolower($album['dir']))){
                        if(count(scandir('imgs/'.strtolower($album['dir'])))>2){
                            echo('<li id="album?a='.strtolower($album['dir']).'" class="menubutton albumb">'.strtoupper($album['name_'.$_SESSION['lang']]).'</li>');
                        }
                    }
                }
            }
            foreach($LANG['menu'] as $Key=>$Val){
                echo('<li id="'.$Key.'" class="menubutton">'.$Val.'</li>');
            } ?>
        </ul>
    </nav>
 </header>
</body>
</html>
