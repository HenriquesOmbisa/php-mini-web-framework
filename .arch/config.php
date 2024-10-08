<?php

define("DS", DIRECTORY_SEPARATOR);
define("DIR_APP", str_replace(".arch","", __DIR__));

/*set your website title*/
define('WEBSITE_TITLE', "My Website");

/*set database variables*/
define('DB_TYPE','mysql');
define('DB_NAME','test_db');
define('DB_USER','root');
define('DB_PASS','');
define('DB_HOST','localhost');

/*protocal type http or https*/
define('PROTOCAL','http');
define('ROOT_APP','C:\xampp\htdocs\\');


/*root and asset paths*/

$path = str_replace("\\", DS ,PROTOCAL ."://". $_SERVER['SERVER_NAME']. DS . DIR_APP);
$path = str_replace(ROOT_APP, "" ,$path);

// $path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);

define('PATH',$path);
// define('ROOT', $path);
define('ASSETS', $path.'public/assets/');

/*set to true to allow error reporting
set to false when you upload online to stop error reporting*/

define('DEBUG',true);

if(DEBUG)
{
	ini_set("display_errors",1);
}else{
	ini_set("display_errors",0);
}