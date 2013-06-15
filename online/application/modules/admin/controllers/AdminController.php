<?php

class Admin_AdminController extends Zend_Controller_Action
{

    function preDispatch()
    {
        if (!Authentication_Manager::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
    }	

	
	public function init() 
	{ 
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();				
    	$this->_helper->layout->setLayout('layout');
	}
		
    public function indexAction()
    {
    }
    
    public function refreshAction() {
    	

		$photosUsed = array();
		    	
    	$file = new FileUtil();
    	$file->setDownloadPath(array('upload'));
    	$fileList = $file->getFileListRelative();
    	$systemFileList = array();
    	foreach ($fileList as $fileName) {
    		$newFile = array();
    		$newFile["file"] = "/".$file->downloadFrom."/".$fileName;
    		$newFile["path"] = $file->getFilePath($fileName);
    		$systemFileList[] = $newFile;
    	}
    	foreach ($systemFileList as $systemFile) {
    		if (!in_array($systemFile["file"],$photosUsed) && $systemFile["file"] != "/upload/images/default/nophoto.jpg") {
    			//@rename($systemFile["path"],$file->baseDownloadPath."/__deleted/".$file->getFileName($systemFile["path"]));
    		}
    	}
		    	    		
    	if (isset(Plugins_Cache::$cacheInstance)) Plugins_Cache::$cacheInstance->clean(Zend_Cache::CLEANING_MODE_ALL);
    	
    }

}