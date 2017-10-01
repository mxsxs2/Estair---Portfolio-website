<?php
include_once'config.php';
include_once'lang/'.$_SESSION['lang'].'.php';
?>
<div id="aboutpic">
    <img src="imgs/about.jpg" alt="Estair" width="320px" height="625px"/>
</div>
<div id="abouttext">
    <?php $row=$mysql->one_row('`about_'.$_SESSION['lang'].'`','about',"1=1",1);
          if($row!=FALSE){
            echo($row[0]);
          }   ?>
</div>