<?php
/**
 * Logging plugin
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Logging extends Zend_Controller_Plugin_Abstract
{

    public function __construct()
    {

    }

    /**
     * Start logging
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {

		$module = strtolower($request->getModuleName());
		$controller = strtolower($request->getControllerName());
		$action = strtolower($request->getActionName());
		$pathInfo = $request->getPathInfo();
                	
    	if (!$request->isGet()) return;
    	if ($module == "admin") return;

    	$db = Zend_Registry::get('dbAdapter');
    
		
		$columnMapping = array( 'datetime' => 'timestamp',
		'user_agent'=> 'user_agent',
		'controller'=> 'thecontroller',
		'action'=> 'theaction',
		'module'=> 'themodule',
		'pathinfo'=> 'thepathinfo',
		'ip'=> 'theip',
		'domain'=> 'thedomain'
		);		

		$writerDb = new Zend_Log_Writer_Db($db, 'website_visits', $columnMapping);
		$logger = new Zend_Log($writerDb);
		
		$logger->setEventItem('timestamp',date('Y-m-d H:i:s'));
		$logger->setEventItem('user_agent',$_SERVER['HTTP_USER_AGENT']);
		$logger->setEventItem('thecontroller',$controller);
		$logger->setEventItem('theaction',$action);
		$logger->setEventItem('themodule',$module);
		$logger->setEventItem('thepathinfo',$pathInfo);
		
		if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		}		
		$domain = $ip;
		
		$logger->setEventItem('theip',$ip);
		$logger->setEventItem('thedomain',$domain);
		
		$logger->info('Informational message');

    }

}
