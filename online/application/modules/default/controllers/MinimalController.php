<?php

class MinimalController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;

	public function init() {
		parent::init();
    	$this->_helper->layout->setLayout('minimal');
    	
		$this->destManager = new Destinations_Manager();
		$this->photoManager = new Photos_Manager();
	}

    public function indexAction()
    {

    	$countries = $this->destManager->getDestinations();
    	
    	$activeCountry = rand(0, count($countries)-1);
    	$places = $countries[$activeCountry]["places"];
    	$activePlace = rand(0, count($places)-1);
    	
    	$this->view->countries = $countries;
    	$this->view->places = $places;
    	$this->view->activeCountry = $countries[$activeCountry];
    	$this->view->activePlace = $places[$activePlace];
    	
    	$this->view->did = $this->view->activePlace["id"];
    	
		// Set the site title
		$this->view->headTitle($this->view->t("faces & places"));
    }
}