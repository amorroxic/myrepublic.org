<?php

/**
 * Gzip plugin
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Plugins_GZip extends Zend_Controller_Plugin_Abstract
{
    /**
     *  @var bool Whether or not to disable caching
     */
    public static $doNotGzip = false;

    /**
     * Check the request
     *
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request instanceof Zend_Controller_Request_Http) {
            self::$doNotGzip = true;
            return;
        }
        $acceptedEncodings = $request->getHeader('Accept-Encoding');
        if (strpos($acceptedEncodings, 'gzip') === false) {
            self::$doNotGzip = true;
            return;
        }
    }

    /**
     * Gzip response
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $response = $this->getResponse();
        if (self::$doNotGzip || $response->isRedirect() || !function_exists('gzencode')) {
            return;
        }
        
        $bodyParts = $response->getBody(true);
        $length = 0;
        
        foreach ($bodyParts as $key => $value) {
            $encodedValue = gzencode($value, 3);
            $response->setBody($encodedValue, $key);
            $length += strlen($encodedValue);
        }
        
        $response->setHeader('Content-Encoding', 'gzip', true)
                 ->setHeader('Content-Length', $length, true);
    }
}
