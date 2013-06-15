<?php

class Zend_View_Helper_LangSwitch extends Zend_View_Helper_Abstract
{
	
	public $view;

	public function langSwitch($content="",$contentID = 0) {

		$front = Zend_Controller_Front::getInstance();
		//$request = $front->getRequest();
		//$controller = $request->getControllerName();
		//$action = $request->getActionName();

		$returnValue = "";
		$languages = Zend_Registry::get("languages");
		$lang = $this->view->language;
		foreach ($languages as $language) {
			if ($language["symbol"] != $lang) {
				$routeName = "translate_".$language["symbol"];
				$returnValue = $this->view->url(array("controller"=>"index", "action"=>"index","@locale"=>$language["symbol"]),$routeName);
				//$returnValue .= "/".$this->view->seoLink($content,$contentID);
				$returnValue = "<div class='right'><a href='".$returnValue."'><img border='0' src='/images/front/header/lang_".$language["symbol"].".png'/></a></div>";
			}
		}
		
		return $returnValue;
				
	}


}
