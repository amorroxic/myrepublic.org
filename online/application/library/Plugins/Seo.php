<?php
class Plugins_Seo extends Zend_Controller_Plugin_Abstract {

	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{

/*
		$front = Zend_Controller_Front::getInstance(); 
		$dispatcher = $front->getDispatcher();
        $class      = $dispatcher->getControllerClass($request);
        if (!$controller) {
            $class = $dispatcher->getDefaultControllerClass($request);
        }
        
        $r      = new ReflectionClass($class);
        $action = $dispatcher->getActionMethod($request);
        if (!$r->hasMethod($action)) {
        	$request->setActionName("index");
        }
*/
		$action = $request->getActionName();
		if (is_numeric($action)) $request->setActionName("content-detail");

	}
}
?>