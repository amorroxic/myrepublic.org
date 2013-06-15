<?php

class Zend_View_Helper_SeoGet extends Zend_View_Helper_Abstract
{

	public function seoGet() {

        $request = Zend_Controller_Front::getInstance()->getRequest();
    	$requestedURI = $request->getRequestURI();

		if (isset($requestedURI)) {

			$uriISFolder = ($requestedURI[strlen($requestedURI)-1] == "/") ? "/" : "";
			$requestedURI = trim($requestedURI, '/') . $uriISFolder;
			$path = explode('/', $requestedURI);
			//$requestedArticle = $path[sizeof($path)-1];

			//$articleQuery = explode('-',$requestedArticle);
			//$articleID = $articleQuery[sizeof($articleQuery)-1];

			$articleID = $path[sizeof($path)-2];
			
			if (is_numeric($articleID)) {
				return $articleID;
			} else {
				return -1;			
			}
			
		} else {
			return -1;
		}
		
	}

}
