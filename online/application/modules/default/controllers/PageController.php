<?php

class PageController extends Site_Navigation_Default_Controller
{
	
	private $locationArray;
		
    public function indexAction()
    {
    	// Get the request
    	$request = $this->getRequest();
    	
    	// Get the reuqested page
    	$requestedPage = $request->getParam(1);

    	// Set the view rendered
		$this->_helper->viewRenderer->setRender($requestedPage);
				
    }


}