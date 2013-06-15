<?php

class Zend_View_Helper_T extends Zend_View_Helper_Abstract
{

	public function t($text="") {

		return Zend_Registry::get("Zend_Translate")->_($text);
		
	}


}
