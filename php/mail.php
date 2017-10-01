<?php
include_once('config.php');
include_once('lang/'.$_SESSION['lang'].'.php');
if(isset($_POST)){
    $POSTS=array('sender'=>"",'name'=>"",'subject'=>"",'message'=>"");
    $respond="";
    foreach($POSTS as $key=>$val){
        if(!isset($_POST[$key]) || !$SECURE->ell($_POST[$key])){
            $respond=$LANG['mail']['missing']."_".$key;
        }
        if($key=="sender"){
            if(!preg_match('/^([a-z0-9]+([_\.\-]{1}[a-z0-9]+)*){1}([@]){1}([a-z0-9]+([_\-]{1}[a-z0-9]+)*)+(([\.]{1}[a-z]{2,6}){0,3}){1}$/i', $_POST['sender'])){ //if its not an email
                $respond=$LANG['mail']['mail']."_".$key; 
            }
        }
        if($key=="message"){
            if(strlen($_POST["message"])<40){                                         //if its sorter than 40 characters
                $respond=$LANG['mail']['min']."_".$key;
            }
            if(isset($_SESSION['message']) && $_SESSION['message']==$SECURE->ell($_POST['message'])){ //if its not the first message
                $respond=$LANG['mail']['error']."_".$key;
            }
        }
        if($respond==""){
            $POSTS[$key]=$SECURE->ell($_POST[$key]);
        }
    }
    if($respond==""){
        $message =$POSTS['name']." <".$POSTS["sender"]."> Ezt küldte:<br><br>".$POSTS["message"];
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Additional headers
        $headers .= 'To: Estair Makeup <estairmakeup@gmail.com>' . "\r\n";
        $headers .= 'From: '.$POSTS['name'].' <'.$POSTS["sender"].'>' . "\r\n";
        // Mail it
        $mail=$mysql->one_row('`email`','contact');
        if(mail($mail['email'], $POSTS["subject"], $message, $headers)){
            $respond=$LANG['mail']['sent']."_1";
            $_SESSION['message']=$message;
        }else{
            $respond=$LANG['mail']['send_err']."_0";
        }
    }
    echo($respond); 
}
    
     
        

             
            
?>