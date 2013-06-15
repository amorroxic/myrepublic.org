<?php

class Flash_AmfController extends Zend_Controller_Action
{

	public function postDispatch ()
	{
	//	shut the layout the fuck off
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();	
	//	set the correct header if no error or forwarding occurred during dispatch
	$this->_response->setHeader('Content-Type', 'application/x-amf', true);
	}	

    public function indexAction()
    {
      $server = new Zend_Amf_Server();
      $server->addDirectory(ROOTDIR . '/application/modules/flash/services/');
      $req = $server->handle(); 
      $this->_response->setBody($req);
    }
    
}