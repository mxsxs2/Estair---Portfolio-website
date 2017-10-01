<?php
include_once'../php/config.php';
include_once'../php/lang/ad_'.$_SESSION['lang'].'.php';
if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=$CONFIGVAR['web'].$CONFIGVAR['SELFDIR']."/" && $_SERVER['HTTP_REFERER']!=$CONFIGVAR['web'].$CONFIGVAR['SELFDIR']."/index.php"){
    //echo("hamis referer");
    //die;   
}
if(isset($_SESSION['login']['proba'])){
    $_SESSION['login']['proba']=0;
    if($_SESSION['login']['proba']>5){
	   ?><script type="text/javascript">window.parent.$('#log_back').html($LANG['login']['block']); </script><?php
	   //die;
    }
}
if(isset($_GET['lu'])){
    unset($_COOKIE['Login']);
    unset($_SESSION['user']);
    $mysql->delete('user_sessions',1,"`session_id`='".session_id()."'");
    $SESSIONM->Regenerate();
	?><script type="text/javascript">window.parent.location.reload()</script></p><?php
}
//if(@isset($_POST[$_SESSION['login']['token']]) && !$SECURE->logined(0)){
    if( !$SECURE->logined(0)){   
    $i=0;
	if(!$SECURE->ell($_POST['user'])) $i++;
	if(!$SECURE->ell($_POST['password'])) $i++;
	//if($_POST[$_SESSION['login']['token']]=="") $i++;
	//if($_POST[$_SESSION['login']['token']]!=$_SESSION['login']['input']) $i++;
	if($i==0){
        if($SECURE->ell($_POST['user'])==$ADMIN['name'] && $SECURE->ell($_POST['password'])==$ADMIN['pass']){
            $cookie=round(time()*strtotime(time()));
            if($cookie<0) $cookie=$cookie*(-1);
            $SESSIONM->Regenerate();
            $values="'".session_id()."', 1,'".$_SERVER['SERVER_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."',".$cookie;
            $u_s=$mysql->one_row('`session_id`','user_sessions','`u_id`=1');
            if($u_s!=FALSE){
                   $mysql->delete('user_sessions',1,'`u_id`=1');
            }
                    $mysql->insert('user_sessions','`session_id`, `u_id`, `ip_adress`, `user_agent`, `cookie`', $values);	 	
                    $_SESSION['user']=$ADMIN['name'];
                    if(isset($_SESSION['login'])) unset($_SESSION['login']);
                    ?><script type="text/javascript">
                        window.parent.$('#log_back').html('<?php echo($LANG['login']['logined']); ?>');
                        window.parent.location.reload();
                      </script><?php
                    
		}else{ 	
			?><script type="text/javascript">window.parent.$('#log_back').html('<?php echo($LANG['login']['wrongdata']); ?>'); </script><?php
			if(isset($_SESSION['login']['proba'])) $_SESSION['login']['proba']++;  //növeljük a belépésikísérletekszámát
		}	
	}else{
	   echo $i;
	}
}else{
    print_r($_SESSION);
    echo("<br><br>");
    print_r($_POST);
    ?><script type="text/javascript">window.parent.$('#log_back').html('token'); </script><?php
}
?>
