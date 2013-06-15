<?php

class Site_Navigation_Default_Controller extends Zend_Controller_Action
{
   
	public function init()
	{
		$uri = $this->_request->getPathInfo();
		$activeNav = $this->view->navigation()->findByUri($uri);
		$activeNav->active = true;
		
		$this->view->language = $this->_request->getParam("language","ro");
		$this->view->controller = $this->_request->getControllerName();

		$knownLanguages = Zend_Registry::get("languages");
		foreach ($knownLanguages as $language) {
			if ($language["symbol"] == $this->view->language) {
				$this->view->languageID = $language["id"];
				$this->view->languageName = $language["name"];
			}
		}
		
		$this->view->headMeta()->appendName('copyright', '2007-'.date('Y'));
		
		
		
	}        	
}