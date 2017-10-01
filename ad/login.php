<?php
$_SESSION['login']['token']=$SECURE->token();
$_SESSION['login']['input']=$SECURE->token(time()+time());
include_once'../php/lang/ad_'.$_SESSION['lang'].'.php';
?>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$("input#esend").click(function(){
	   $.ajax({
                    url : 'login_p.php',
                    type: 'POST',
                    data: 'user='+$('#user_form').val()+'&password='+$('#pass_form').val()+'&<?php echo($_SESSION['login']['token']); ?>='+$('#token').val(),
                    dataType: 'html',
                    beforeSend: function(){},
                    complete: function(){},
                    success: function(html){
                        //document.location.reload();
                        $("p#back").html(html);
                    }
                });
	});
    $(".i").click(function(){
       $("#log_back").html(''); 
    });
});    
-->
</script>
<!-- Login--------------------------------------------------------------------------------------------!>
<p id="back">&nbsp;</p>
<form id="loginform" method="POST" action="login_p.php" target="hide">
    <legend id="loginlegend"><?php echo($LANG['login']['login']); ?></legend>
    <legend id="log_back"></legend>
    <input id="user_form" class="i" type="text" name="user" value="<?php echo($LANG['login']['username']); ?>"/></td>
    <input id="pass_form" class="i" type="password" name="password" value="<?php echo($LANG['login']['password']); ?>"/></td>
    <input id="esend" type="button" name="submit" value="<?php echo($LANG['login']['login']); ?>"/></td> 
    <input id="token" type="hidden" name="<?php echo($_SESSION['login']['token']); ?>" value="<?php echo($_SESSION['login']['input']); ?>"/>
</form>