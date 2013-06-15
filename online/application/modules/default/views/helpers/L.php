<?php

class Zend_View_Helper_L extends Zend_View_Helper_Abstract
{
	
	public $view;

	public function l($controller="index", $action="index", $content=array()) {

		$lang = $this->view->language;
		$routeName = "translate_".$lang;
		$returnValue = $this->view->url(array("controller"=>$controller, "action"=>$action),$routeName, true);
		if (isset($content[$lang])) 
		$returnValue .= "/".$this->view->seoLink($content[$lang]["body"],$content[$lang]["id"]);
		return $returnValue;
				
	}


}
