<?php

class IndexController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;

	public function init() {
		parent::init();
		$this->destManager = new Destinations_Manager();
		$this->photoManager = new Photos_Manager();
	}

    public function indexAction()
    {

    	$countries = $this->destManager->getCountriesWithPlaces();
    	
    	$this->view->countries = $countries;
    	
		// Set the site title
		$this->view->headTitle($this->view->t("Visual guide to your favorite destinations."));		

		$this->view->headMeta()->appendName('description', 'High quality images taken around the world. As a visual guide we aim to highlight beautiful places and real life opinions expressed by fellow travellers.');

		$this->view->headMeta()->appendName('keywords', 'photos photography travel opinions places destinations guide world countries');

    }
}