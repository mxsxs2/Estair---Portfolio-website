<?php
@session_start();
ini_set("default_charset","utf-8");
header('Content-Type: text/html; charset=utf-8');
include_once('function.php');
include_once('sessionmanager.php');
try{
    /*$mysql_host = "mysql10.000webhost.com";
    $mysql_database = "a1865883_estair";
    $mysql_user = "a1865883_root";
    $mysql_password = "9204220313182a";*/
    
    $mysql_host = "localhost";
    $mysql_database = "estair";
    $mysql_user = "root";
    $mysql_password = "";
    $mysql=new mysql($mysql_password, $mysql_user, $mysql_database, $mysql_host);
}catch(MysqlErrorException $e){
    if($e->getMessage()==1){
        echo('<html>
					<head>
						<title>Adatbázis hiba</title>
						<script type="text/javascript">
								var x=5;
							function load(){
								if(x==0){
									document.getElementById("back").innerHTML="-";
									document.location.reload();
								}else{
									document.getElementById("back").innerHTML=x;
								}
								x--;
								setTimeout("load()",1000);
							}
						</script>
					</head>
					<body bgcolor="#01336A" text="#FF0000" onLoad="load()">
						<p align="center">
							<b><font size="6">Nem sikerült csatlakozni az adatbázishoz.</font></b>
							<br>
							Az oldal újrafrissíti magát <a id="back"></a> másodpercen belül.
						</p>
					</body>
				</html>');
			die;
   }
}
$SESSIONM= new SessionManager();
Class Answer Extends Exception{}
mysql_query("SET NAMES `utf8`");
@ini_set("display_errors",true);
@ini_set("register_globals",false);
@ini_set("log_errors",true);
//@ini_set("magic_quotes_gpc",false);
@ini_set("safe_mode",true);
ini_set("safe_mode_gid",false);
ini_set("allow_url_fopen",false);
@ini_set("output_buffering",true);
@ini_set("safe_mod_grid",false);
@ini_set("enable_dl",false);
@error_reporting(E_ALL);
//-------------------------------változók
$ADMIN['pass']="pss0321";
$ADMIN['name']="Eszter";
$size_bytes = 10490000; //maximum fájl méret bájtokban
$limitedext = array(".gif",".jpg",".jpeg",".png",".bmp",".swf"); //engedélyezett fájl kiterjesztések
$th_width= 100; //thumbnail szélessége
$th_height= 170; //thumbnail hamaggásga
$CONFIGVAR=array(
        /*'web'     => "http://estair.mxsxs2.info",
        'SELFDIR' => ''*/
        'web'     => "http://176.61.83.49/",
        'SELFDIR' => 'eszter'
);
$SECURE=new secure($CONFIGVAR,$SESSIONM);
?>