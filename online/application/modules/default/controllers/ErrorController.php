<?php

class ErrorController extends Site_Navigation_Default_Controller
{

	public function init() {
		parent::init();
	}
	
    public function errorAction()
    {
		// Clear the response body
		$this->getResponse()->clearBody();

		// Set the default layout
    	$this->_helper->layout->setLayout('layout');
		
		// Get the error message
    	$errors = $this->_getParam('error_handler');
    	$exception = $errors->exception;
		$this->view->zend_error_message = $exception;
		
		// Log the error
		try {
			if (!$exception instanceof Zend_Controller_Dispatcher_Exception) {
				Logger::get()->err($exception);
			} else {
		    	Logger::get()->warn($exception);
			}
		} catch (Exception $ex) {
			$this->view->zend_error_message .= "\n\n" . $ex;
		}
	}
}