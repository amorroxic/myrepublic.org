<?php

class Admin_ComentariiController extends Zend_Controller_Action
{

	public $categoryManager;
	public $comentariiManager;

    function preDispatch()
    {
        if (!Auth::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
            return;
        }
    	$this->comentariiManager = new Galleries_Comments_Manager();

    }
		
	public function init() 
	{ 
    	// Set the layout
    	$this->_helper->layout->setLayout('page');	
    		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('perform', 'json');
		$ajaxContext->initContext();				
				
	}
		    
    public function indexAction() {

		$this->view->headerText = "Comentarii";

		$request 	= $this->getRequest();    	
		$page 		= $request->getParam("page","1");
		$filtru		= $request->getParam("filtru","neaprobate");
		
		$params = $request->getParams();
		$queryParams = array();
		foreach ($params as $key => $value) {
			switch (strtolower($key)) {
				case "module":
								break;
				case "controller":
								break;
				case "action":
								break;
				default:
								$queryParams[$key] = $value;
								break;
			}
		}		
		$this->view->queryParams = $queryParams;		

    	$cauta = $request->getParam("cauta","");
    	if ($cauta != "") {
    		$this->comentariiManager->setSearchString($cauta);
    	}
    	
		$this->comentariiManager->setFilter($filtru);

		$this->view->comments = $this->comentariiManager->getComments();
    	$this->view->filtru	= $filtru;
    	$this->view->page	= $page;

		$this->view->comments = Zend_Paginator::factory($this->view->comments);
		$this->view->comments->setCurrentPageNumber($page);
		$this->view->comments->setItemCountPerPage(10);
		$this->view->comments->setPageRange(5);
    	
    	$this->view->paginator = $this->view->comments;
    	
    }    
    
    public function aprobaAction() {
									
		$request 	= $this->getRequest();    	
		$page 		= $request->getParam("page","1");
		$filtru		= $request->getParam("filtru","neaprobate");
		$id 		= $request->getParam("id","");
		$message 	= $request->getParam("message","");
		$uid 		= $request->getParam("uid","");

		$statement = array(
		   'approved' => "1"
		);
		$where = "id = " . $id;
		$this->comentariiManager->table->update($statement, $where);
		
		$this->_redirect('/admin/comentarii/index/filtru/'.$filtru.'/page/'.$page);    


    }
    
    public function stergeAction() {
    
		$request 	= $this->getRequest();    	
		$page 		= $request->getParam("page","1");
		$filtru		= $request->getParam("filtru","neaprobate");
		$id 		= $request->getParam("id","");
		$message 	= $request->getParam("message","");
		$uid 		= $request->getParam("uid","");

        $where = 'id = ' . $id;
		$this->comentariiManager->table->delete($where);
		
		$this->_redirect('/admin/comentarii/index/filtru/'.$filtru.'/page/'.$page);    

    
    }
    
}