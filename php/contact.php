<?php
include_once('config.php');
include_once('lang/'.$_SESSION['lang'].'.php');
?>
<p id="back">&nbsp;</p>
<form id="contform">
    <input type="text" name="ename" id="ename" class="input" value="<?php echo($LANG['contact']['name']); ?>" placeholder="<?php echo($LANG['contact']['name']); ?>"/><br />
    <input type="text" name="email" id="email" class="input" value="<?php echo($LANG['contact']['mail']); ?>" placeholder="<?php echo($LANG['contact']['mail']); ?>"/><br />
    <input type="text" name="esubject" id="esubject" class="input" value="<?php echo($LANG['contact']['subject']); ?>" placeholder="<?php echo($LANG['contact']['subject']); ?>"/><br />
    <textarea name="etext" id="etext"></textarea>
    <input type="button" name="esend" id="esend" value="<?php echo($LANG['contact']['send']); ?>"/><br />
</form>
<div id="contact_box">
    <p id="mail"><?php echo($LANG['contact']['contme']); ?></p>
    <?php 
    $cont=$mysql->one_row('`phone`,`email`','contact');
    if(is_array($cont)){
        echo($cont['email']."<br>".$cont['phone']);
    }
    ?>
</div>