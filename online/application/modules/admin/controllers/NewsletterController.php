<?php

class Admin_NewsletterController extends Zend_Controller_Action
{

	public $tagManager;
	public $contentManager;
	public $pagesManager;

	public $activeSection;
		
    function preDispatch()
    {
        if (!Auth::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
        }
    }			
		
	public function init() 
	{ 
    	// Set the layout
    	$this->_helper->layout->setLayout('page');	
    		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('perform', 'json');
		$ajaxContext->initContext();		
			
		$this->view->headerText = "Newsletter";
	
	}
		
    public function indexAction()
    {
    	$newsletterManager = new Newsletter_Manager();
    	$this->view->emails = $newsletterManager->getEmailBlocks();
    }
    
    public function trimiteAction() {

    	$newsletterManager = new Newsletter_Manager();
    	$this->view->emails = $newsletterManager->getEmailBlocks();
    
    	$request = $this->getRequest();
    	$toSiteUsers = $request->getParam("subscribers","");
    	$toSubscribers = $request->getParam("newsletter","");
    	$newsContent = $request->getParam("newsletter_content","");
    	$subiect = $request->getParam("subiect","");
    	
    	$newsletterArray = array();
    	
    	if ($toSubscribers != "") {
    		$newsletterManager = new Newsletter_Manager();
    		$emails = $newsletterManager->getEmailBlocks();
    		foreach ($emails as $email) {
    			$newsletterArray[] = $email["email"];
    		}
    	}
    	
    	if ($toSiteUsers != "") {
    		$userManager = new Users_Manager();
    		$users = $userManager->getUsers();
    		foreach ($users as $user) {
    			$mail = $user["email"];
    			if ($mail != "" && !in_array($mail,$newsletterArray)) $newsletterArray[] = $mail;
    		}
    	}
    	
    	$newsContent = stripslashes($newsContent);
    	
    	//$transport = new Zend_Mail_Transport_Smtp('localhost');
    	
    	//$paginator = Zend_Paginator::factory($newsletterArray);
		//$paginator->setItemCountPerPage(20);
		//$pages = $paginator->getPages();
		/*
		for ($i=1; $i<=$pages->pageCount; $i++) {
			$paginator->setCurrentPageNumber($i);

		    $mail = new Zend_Mail('utf-8');
		    $mail->addTo('newsletter@phototravel.ro', 'PhotoTravel newsletter');
			$mail->setFrom('newsletter@phototravel.ro', 'PhotoTravel newsletter');
			$mail->setSubject($subiect);
			foreach($paginator as $email) {
				$mail->addBcc($email);
			}

		    $mail->setBodyHtml($newsContent);
		    $mail->send($transport);

		} */

		for ($i=0; $i<count($newsletterArray); $i++) {

			if ($newsletterArray[$i] != "") {
			
				$cronEmail = new Cron_Email_Table();
				$statement = array(
				   'to'			=>$newsletterArray[$i],
				   'body'		=>$newsContent,
				   'subject'	=>$subiect
				);
				$cronEmail->insert($statement);

			}

		}
		   	    	
    	
    }
    
    public function cronAction() {
    }
    
    
}