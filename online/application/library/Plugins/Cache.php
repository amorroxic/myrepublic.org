<?php
/**
 * Caching plugin
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Plugins_Cache extends Zend_Controller_Plugin_Abstract
{
    /**
     *  @var bool Whether or not to disable caching
     */
    public static $doNotCache = false;
    public static $cacheInstance;

    /**
     * @var Zend_Cache_Frontend
     */
    public $cache;

    /**
     * @var string Cache key
     */
    public $key;

    /**
     * Constructor: initialize cache
     * 
     * @param  array|Zend_Config $options 
     * @return void
     * @throws Exception
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            throw new Exception('Invalid cache options; must be array or Zend_Config object');
        }

        if (array('frontend', 'backend', 'frontendOptions', 'backendOptions') != array_keys($options)) {
            throw new Exception('Invalid cache options provided');
        }

        $options['frontendOptions']['automatic_serialization'] = true;

        $this->cache = Zend_Cache::factory(
            $options['frontend'],
            $options['backend'],
            $options['frontendOptions'],
            $options['backendOptions']
        );
        self::$cacheInstance = $this->cache;
    }

    /**
     * Start caching
     *
     * Determine if we have a cache hit. If so, return the response; else,
     * start caching.
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isGet()) {
            self::$doNotCache = true;
            return;
        }
		$module = strtolower($request->getModuleName());
		$controller = strtolower($request->getControllerName());
		$action = strtolower($request->getActionName());
        if ($module == "admin" || ($controller == "editii" && $action == "download") || ($controller == "auth")) {
            self::$doNotCache = true;
            return;
        }
		
        $path = $request->getPathInfo();

        $this->key = md5($path);
        if (false !== ($response = $this->cache->load($this->key))) {
            $response->sendResponse();
            exit;
        }
    }

    /**
     * Store cache
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        if (self::$doNotCache
            || $this->getResponse()->isRedirect()
            || (null === $this->key)
        ) {
            return;
        }

        $this->cache->save($this->getResponse(), $this->key);
    }
}
