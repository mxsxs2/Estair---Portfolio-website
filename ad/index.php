<?php

include_once'../php/config.php';
include_once('../php/lang/langset.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="icon" href="css/img/earth.png" type="image/x-icon"/>
<meta charset="utf-8" />
<title>ESTAIR</title>
<!--[if IE 6]><base href="<?php echo($CONFIGVAR['web'].$CONFIGVAR['SELFDIR']);?>/ad/"></base><![endif]-->
<!--[if !IE 6]><!--><base href="<?php echo($CONFIGVAR['web'].$CONFIGVAR['SELFDIR']);?>/ad/" /><!--<![endif]-->
<link href="../css/index.css" rel="stylesheet" type="text/css" />
<link href="../css/admin.css" rel="stylesheet" type="text/css" />
<link href="../css/box_ad.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../js/index.js"></script>
<script type="text/javascript" src="../js/boxmini.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>
</head>
<body>
 <header id="frame">
    <div id="bg">
    <section id="leftside">
        <div id="namecont">
            <p id="name"><?php echo($LANG['index']['name']); ?></p>
            <p id="label"><?php echo($LANG['index']['label']); ?></p>
             <?php
                if($SECURE->logined(0)){
                    ?><input type="button" class="button" value="<?php echo($LANG['login']['logout']); ?>" id="logout"/><?php
                }
             ?>
        </div>
    </section>
    <section id="content">
    <?php
    if(!$SECURE->logined(0)){
        include('login.php');
    }else{
        $_SESSION['admin']['token']=$SECURE->token();
        $_SESSION['admin']['input']=$SECURE->token(time()+time());
        include_once('settings.php');
    ?>
        <table id="cat_tree">
            <tr>
                <td class="cat_open" id="home">
                    <?php echo($LANG['menu']['home']); ?>
                </td>
            </tr>
            <tr>
                <td id="cat_home" class="cat_hidden cat_cont"></td>
            </tr>
            <tr>
                <td class="cat_open" id="album">
                    <?php echo($LANG['cat']['album']); ?></td>
            </tr>
            <tr>
                <td id="cat_album" class="cat_hidden cat_cont"></td>
            </tr>
            <tr>
                <td class="cat_open" id="about">
                    <?php echo($LANG['menu']['about']); ?></td>
            </tr>
            <tr>
                <td id="cat_about" class="cat_hidden cat_cont"></td>
            </tr>
            <tr>
                <td class="cat_open" id="contact">
                    <?php echo($LANG['menu']['contact']);?></td>
            </tr>
            <tr>
                <td id="cat_contact" class="cat_hidden cat_cont"></td>
            </tr>
        </table>
    <?php } ?>
    </section>
    </div>
 </header>
 <iframe name="hf" width="0" height="0" id="hf"></iframe>
</body>
</html>