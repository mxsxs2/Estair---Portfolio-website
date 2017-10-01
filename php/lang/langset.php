<?php
/**
 * @author mxsxs2
 * @copyright 2011
 */
@session_start();
if(isset($_GET['l'])){
    if($_GET['l']=="hun") $_SESSION['lang']='hun';
    if($_GET['l']=="en") $_SESSION['lang']='en';
    ?><script>parent.document.location.reload();</script><?php
}
If(!isset($_SESSION['lang'])){
    if($_SERVER['HTTP_ACCEPT_LANGUAGE']!=" " && $_SERVER['HTTP_ACCEPT_LANGUAGE']!=NULL){
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        switch ($lang){
            case "hu":
                $_SESSION['lang']='hun';
                break;        
        default:
            $_SESSION['lang']='en';
            break;
        }
    }
}
    @include_once('php/lang/'.$_SESSION['lang'].'.php');
    @include_once('../php/lang/'.$_SESSION['lang'].'.php');
?>