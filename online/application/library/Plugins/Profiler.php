<?php

/**
 * Profiler plugin
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Plugins_Profiler extends Zend_Controller_Plugin_Abstract
{
    /**
     *  @var bool Whether or not to disable caching
     */
    public $_startTime = null;
    public $_startMemory = null;

    /**
     * Check the request
     *
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        global $startTime, $memoryUsage;
        Logger::get('debug')->debug('Memory consumption at start: ' . ceil($memoryUsage / 1024));
        $time = microtime(true) - $startTime;
        Logger::get('debug')->debug('Time for initialization: ' . round($time, 3));
        
        $this->_startTime = microtime(true);
        $this->_startMemory = memory_get_usage();
        Logger::get('debug')->debug('Memory consumed for initialization: ' . ceil(($this->_startMemory - $memoryUsage) / 1024));
        Logger::get('debug')->debug('Memory consumption at controller start: ' . ceil(memory_get_usage() / 1024));
    }

    /**
     * Dispatch response
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $time = microtime(true) - $this->_startTime;
        Logger::get('debug')->debug('Memory consumption at controller end: ' . ceil(memory_get_usage() / 1024));
        Logger::get('debug')->debug('Memory consumed in controller: ' . ceil((memory_get_usage() - $this->_startMemory) / 1024));
        Logger::get('debug')->debug('Time in controller: ' . round($time, 3));
    }
}
