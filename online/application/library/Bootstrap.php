<?php 

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Initialize the View
	 * @return Zend_View
	 */
    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    /**
     * Initialize the database
     * @return void
     */
    protected function _initDatabase()
    {
	    // Register the default database adapter
		Zend_Db_Table::setDefaultAdapter(PDO_Database::getInstance());
		
		// Check if xcache is loaded
		if (extension_loaded('xcache')) {
		    // Create an XCache cache
		    $metadataCache = Zend_Cache::factory(
							'Core',
		                    'XCache', 
		                    array(
		                    	'lifetime' => 3600, 
		   						'automatic_serialization' => true
		                    ), 
		                    array());
		
			// Set the Zend_Db_Table metadata cache
			Zend_Db_Table::setDefaultMetadataCache($metadataCache);                
		}
		
    }
    
    /**
     * Initialize the front controller plugins
     * @return void
     */
    protected function _initControllerPlugins()
    {
        // Ensure the front controller is initialized
        $this->bootstrap('FrontController');
        
        // Retrieve the front controller from the bootstrap registry
        $controller = $this->getResource('FrontController');

		// Register the profiler plugin
		if (APPLICATION_ENV != 'production') {
			$controller->registerPlugin(new Plugins_Profiler());
		}
		
		// Register the GZip plugin
		$controller->registerPlugin(new Plugins_GZip());
		
		// Register the cache plugin
		if (APPLICATION_ENV != 'development') {
			$cacheOptions = require(ROOTDIR . '/configuration/cache.conf.php');
			$controller->registerPlugin(new Plugins_Cache($cacheOptions));
		}
		
		$controller->registerPlugin(new Plugins_Multilanguage());
		
		$layoutModulePlugin = new Plugins_Layout();
		$layoutModulePlugin->registerModuleLayout('admin','../application/layouts/admin');
		$layoutModulePlugin->registerModuleLayout('default','../application/layouts/default');
		$layoutModulePlugin->registerModuleLayout('flash','../application/layouts/flash');
		$controller->registerPlugin($layoutModulePlugin);
		
		$controller->registerPlugin(new Plugins_Seo());
		
    }
    
    /**
     * Initialize the navigation
     * @return void
     */
    protected function _initNavigation()
    {
    	// Ensure the view is initialized
    	$this->bootstrap('layout');
    	
    	// Retrieve the view
    	$layout = $this->getResource('layout');
    	
    	// Get the view
    	$view = $layout->getView();
    	
    	// Load the navigation XML file
    	$config = new Zend_Config_Xml(ROOTDIR . '/configuration/navigation.xml', 'nav');
    	
    	// Initialize the navigation
    	$navigation = new Zend_Navigation($config);
    	$view->navigation($navigation);
    	
        // Return it, so that it can be stored by the bootstrap
        return $navigation;
    }
    
	protected function _initRouter()
	{

        // Ensure the front controller is initialized
        $this->bootstrap('FrontController');
        
        // Retrieve the front controller from the bootstrap registry
        $controller = $this->getResource('FrontController');
        
	    $router = $controller->getRouter();

		// Prepate the translator
		$translator = new Zend_Translate('gettext', ROOTDIR . '/languages/ro.mo','ro');
		$translator->addTranslation(ROOTDIR . '/languages/en.mo','en');

		// Set the current locale for the translator
		Zend_Registry::set('Zend_Translate', $translator);
		
		$languageManager = new Languages_Manager();
		$languages = $languageManager->getLanguageList();
		Zend_Registry::set("languages",$languages);
		$router->removeDefaultRoutes();

	    $route = new Zend_Controller_Router_Route(
	    	':@controller/:@action/*',
	    	array(
	    		'controller' 	=>'index',
	    		'action' 		=>'index'
	    	)
	    );
		$router->addRoute('default', $route);

		foreach ($languages as $language) {

		    $route = new Zend_Controller_Router_Route(
		    	$language["symbol"].'/:@controller/:@action/*',
		    	array(
		    		'controller' 	=>'index',
		    		'action' 		=>'index',
		    		'language'		=>$language["symbol"]
		    	)
		    );
			$router->addRoute('translate_'.$language["symbol"], $route);
		}
		

	    $route = new Zend_Controller_Router_Route(
	    	'admin/:@controller/:@action/*',
	    	array(
	    		'module'		=>'admin',
	    		'controller' 	=>'index',
	    		'action' 		=>'index'
	    	)
	    );
		$router->addRoute('admin', $route);	

	    $route = new Zend_Controller_Router_Route(
	    	'flash/:@controller/:@action/*',
	    	array(
	    		'module'		=>'flash',
	    		'controller' 	=>'index',
	    		'action' 		=>'index'
	    	)
	    );
		$router->addRoute('flash', $route);	
		
		// Route .html file requests through the page controller
		$route = new Zend_Controller_Router_Route_Regex(
		    '(.+)(\.html)',
		    array(
		        'controller' => 'page',
		        'action'     => 'index'
		    )
		);
		$router->addRoute('page_route', $route);	

		// Set it as default translator for routes
		Zend_Controller_Router_Route::setDefaultTranslator($translator);
		
	    // Returns the router resource to bootstrap resource registry
	    return $router;
	    
	}    
}
