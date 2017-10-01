<?php

/**
 * @author mxsxs2
 * @copyright 2012
 */
 
class SessionManager extends mysql{
    Private $ID=0;
    public function __Construct(){
    }
    public function Regenerate(){
        $id=sha1(time()*time()*rand(0,10000));
        $sess=$_SESSION;
        session_regenerate_id();
        session_destroy();
        session_id($id);
        session_start();
        $_SESSION=$sess;
        return TRUE;
    }
    public function validate(){
        $datas=$this->one_row('*','user_sessions', "`session_id`='".session_id()."'");
        if($datas['session_id']==session_id()){
            if($datas['ip_adress']==$_SERVER['SERVER_ADDR']){
                if($datas['user_agent']==$_SERVER['HTTP_USER_AGENT']){
                    $this->update('user_sessions','`modified`=now()', 1, "`session_id`='".session_id()."'");
                    return true;
                }
            }
        }
        return true;
        //return false;
    }
    public function id(){
        $id=$this->ID;
        if($id>0){
            return $this->ID;
        }else{
            $sel=$this->one_row('`u_id`', 'user_sessions',"`session_id`='".session_id()."'");
            if($sel!=FALSE){
                $this->ID=$sel['u_id'];
                return $sel['u_id'];
            }
        }
        return false;
    }
}



?>