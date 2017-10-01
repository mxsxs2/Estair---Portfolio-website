<?php
//--------------------------------------------------------protect_functions----------------------------------------------
class Secure extends mysql{
    private $number=1234;
    private $number2=13;
    private $CONFIGVARS;
    private $SESSIONM; 
    public function __Construct($configvars,$sessionm){
        $this->CONFIGVARS=$configvars;
        $this->SESSIONM=$sessionm;
    }
    public function cookie_set($sess){
            setcookie('Login',$sess,0,'/', $this->CONFIGVARS['web'],0,1);
    }
    public function ell($string){
  	     $string=trim($string);
  	     if (!preg_match("/[a-zA-Z0-9áéíóöőúüűÁÉÍÓÖŐÚÜŰ._-]/i",$string)) return FALSE; 
         if (strlen($string)<1) return FALSE;
         return mysql_real_escape_string(htmlspecialchars($string), parent::$link);
    }
    public function ell_n($numeric){
         $numeric=trim($numeric);
         if($numeric===0) return $numeric;
         if(!is_numeric($numeric)) return FALSE;
	     if (!preg_match("/[0-9]/",$numeric)) return FALSE;
         return str_replace("-", "", str_replace("+", "", $numeric)); 
    }
    public function logined($n){
       $i=0; 
       if(!$this->SESSIONM->validate() || !isset($_SESSION['user'])){
            $i++;
       } 
       if($i>0){
            if($n==0){
                return FALSE;
            }else{
                echo("<p align='center'>Please relog.</p>");
                die;
            }
       }
            return TRUE;
    }
    public function token($token=""){
        if($token=="") $token=microtime();
        return sha1(md5($token));
    }
    public function token_chk($POST, $LANG){
        if($POST[$SESSION['token']]){
            if($POST[$_SESSION['token']]!=$_SESSION['input']){
                throw new Answer($LANG['badtoken']);
                return FALSE;
            } 
        }else{
            throw new Answer($LANG['messtoken']);
            return FALSE;
        }
        return TRUE;
    }
    public function mail($mail){
        //if(preg_match('/^([a-z0-9\._-])+@([a-z0-9\._-])+([a-z0-9]){2,4}$/', $mail)) return $mail;
        //'/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
        if(preg_match('/^([a-z0-9\._-])+@([a-z0-9_-])+\.([a-z0-9]){2,4}$/', $mail)) return $mail;
        return false;
    }   
}
//------------------------------------------------------------other------------------------------------------------------
function lang(){
    /*$col=mysql_query("select COLUMN_NAME from information_schema.COLUMNS where TABLE_NAME='album'");
    $langs=array();
    while($cols=mysql_fetch_array($col)){
         $a1=explode("_",$cols['COLUMN_NAME']);
         if(isset($a1[1]) && !in_array($a1[1],$langs)){
             array_push($langs,$a1[1]);
         }
    };*/
    $langs=array('en','hun');
    return $langs;
}
class delete{           //-----------------------Dir delete
public static function deleteDir($dirPath) {
    if(!is_dir($dirPath)) return False;
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    if(rmdir($dirPath))    return TRUE;
    return FALSE;
}}
//---------------------------------------------------mysql class------------------------
class MysqlErrorException Extends Exception{}
class mysql{
    public static $link;
    private $LIMIT="";
	public function __Construct($jelszo, $felhaszn, $db, $host="localhost"){
		self::$link=@mysql_connect($host, $felhaszn, $jelszo);
		if(!self::$link){
   		    throw new MysqlErrorException('1');
            return FALSE;
		}else{
			$db_conn=@mysql_select_db($db, self::$link);
            return true;
			if(!$db_conn){ 
				throw new MysqlErrorException('2');
                return FALSE;
			}
		}
	}
    //-----------------------------------------query-s----------------
    //----------------------------------------------------------------------select
    public function select($cells, $table, $limit, $condition='', $orderby=""){
        if($condition!=''){
            $condition="WHERE ".$condition;
        }
        if($limit>0)    $this->LIMIT=" LIMIT ".$limit;
        if($orderby!="") $orderby=" ORDER BY ".$orderby;
        $select=mysql_query('SELECT '.$cells.' FROM `'.$table.'` '.$condition.' '.$orderby.' '.$this->LIMIT, self::$link);
        //echo mysql_error();
        //echo 'SELECT '.$cells.' FROM `'.$table.'` '.$condition.' '.$orderby.' '.$this->LIMIT.' <br>'. mysql_error();
        $this->LIMIT='';
        if(mysql_num_rows($select)>0) return $select;
            return FALSE;
    }
    //-----------------------------------------------------------------------insert
    public function insert($table, $cells, $values){
        $insert=@mysql_query('INSERT INTO `'.$table.'` ('.$cells.') VALUES ('.$values.')', self::$link);
        echo('INSERT INTO '.$table.' ('.$cells.') VALUES ('.$values.') <br/>'. mysql_error().'<br/>');
        if($insert==TRUE) return $insert;
        return FALSE;

    }
    //-----------------------------------------------------------------------update
    public function update($table, $sets, $limit, $condition=""){
        if($condition!="")     $condition=" WHERE ".$condition;
        if($limit>0)           $this->LIMIT=" LIMIT ".$limit;
        $update=mysql_query("UPDATE ".$table." SET ".$sets."".$condition."".$this->LIMIT, self::$link);
        //echo("UPDATE `".$table."` SET ".$sets."".$condition."".$this->LIMIT. '<br/>'. mysql_error());
        if(!$update) return FALSE;
        return TRUE;
        
    }
    //-----------------------------------------------------------------------delete
    public function delete($table, $limit, $condition=""){
        if($condition!="")    $condition=" WHERE ".$condition;
        if($limit>0)          $this->LIMIT=" LIMIT ".$limit;
        $delete=mysql_query("DELETE FROM ".$table.$condition.$this->LIMIT, self::$link);
        //echo("DELETE FROM ".$table.$condition.$limit);
        echo(mysql_error());
        if($delete)        return TRUE;
        return FALSE;
    }
    //------------------------------------------------------------------------one_row
    public function one_row($cells, $table, $condition='', $orderby=''){
        $sel=$this->select($cells, $table, 1, $condition, $orderby);
        //echo("<br>".$cells.", ".$table.", 1, ".$condition."<br>".mysql_error()."<br>");
        if($sel!=FALSE) return mysql_fetch_array($sel);
        return FALSE;
    }
}
?>