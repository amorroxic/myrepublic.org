<?php

/**
 * Bootstrap file
 */ 

// Set the error reporting level
error_reporting(E_ALL);
// Set the root path
$rootDirectory = dirname(__FILE__);
$publicDirectory = "public_html";
$rootDirectory = substr($rootDirectory, 0, strlen($rootDirectory) - strlen($publicDirectory) - 1);
define("ROOTDIR", $rootDirectory);

// Define the default include path
set_include_path(ROOTDIR . '/application/library/' . 
				 PATH_SEPARATOR . 
                 ROOTDIR . '/application/library/pel' . 
                 PATH_SEPARATOR . 
                 ROOTDIR . '/application/modules/default/models/' . 
                 PATH_SEPARATOR . 
                 ROOTDIR . '/application/modules/galerie/models/' . 
				 PATH_SEPARATOR . 
				 ROOTDIR . '/application/modules/galerie/services/' .                 
                 PATH_SEPARATOR . 
				 ROOTDIR . '/application/modules/admin/models/');				


require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true); 
//$loader->registerNamespace('App_');

$config = include(ROOTDIR . '/configuration/database.conf.php');
$db = @mysql_connect($config["host"],$config["username"],$config["password"]);
@mysql_select_db($config["dbname"],$db);

$transport = new Zend_Mail_Transport_Smtp('localhost');

$sql = "select * from 1_cron_email order by id asc limit 0,7";
$res = @mysql_query($sql,$db);
while ($row = @mysql_fetch_array($res)) {

    $mail = new Zend_Mail('utf-8');
    $mail->addTo($row["to"], '');
	$mail->setFrom('contact@photolife.ro', 'PHOTOLIFE newsletter');
	$mail->setSubject($row["subject"]);
    $mail->setBodyHtml($row["body"]);
    $mail->send($transport);

	$sql = "delete from 1_cron_email where id=".$row["id"];
	$delres = @mysql_query($sql,$db);
	
}


?>