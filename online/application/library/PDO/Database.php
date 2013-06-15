<?php

class PDO_Database
{
	
	/**
	 * @var Zend_Db_Adapter_Abstract
	 */
	private static $_adapter;

	/**
	 * Returns a Zend database adapter instance
	 *  
	 * @return Zend_Db_Adapter_Abstract
	 */
	public static function getInstance()
	{
		if (is_null(self::$_adapter)) {		        
			// Load the configuration settings
			$config = include(ROOTDIR . '/configuration/database.conf.php');
			// Create a new database adaptor
			$db = Zend_Db::factory('Pdo_Mysql', $config);
			
			if ($config['profiler']) {			
			
				$profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
				$profiler->setEnabled(true);
				
				// Attach the profiler to your db adapter
				$db->setProfiler($profiler);			
			
			}
			
			$db->query("SET NAMES 'utf8'");			
						
			Zend_Registry::set('dbAdapter', $db);
			
			// Set the adapter
			self::$_adapter = $db;			
		}

		// Return an adapter instance
		return self::$_adapter;	
	}
			
}