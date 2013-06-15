<?php
class Plugins_Multilanguage extends Zend_Controller_Plugin_Abstract {

    public function routeStartup($request)
    {
    
    
		$knownLanguages = Zend_Registry::get("languages");
        $url = $request->getRequestUri();
       	//$url = substr($url,(strpos($url,'/') + 1));
        $params = explode("/",$url);
        $lang = strtolower($params[1]);

		$found = false;
        foreach ($knownLanguages as $language) {
        	if (strtolower($lang) == $language["symbol"]) {
		        $translator = Zend_Registry::get("Zend_Translate");
		        $translator->setLocale($lang);
	        	$request->setParam("language",$lang);
	        	$found = true;
        	}
        }
        
        if (!$found) {
        	$lang = $knownLanguages[0]["symbol"];
	        $translator = Zend_Registry::get("Zend_Translate");
	        $translator->setLocale($lang);
        	$request->setParam("language",$lang);
        }
     
		/*
        if (in_array($lang,$knownLanguages)) {
        	//found a language
        	array_shift($params);
        	array_shift($params);
        	array_unshift($params,"");
        	$request->setParam("language",$lang);
        }
        $newURL = implode("/",$params);
        $request->setRequestUri($newURL);
		*/
                
    }

    public function dispatchLoopStartup(
        Zend_Controller_Request_Abstract $request)
    {    
    }



	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
	}
}
?>