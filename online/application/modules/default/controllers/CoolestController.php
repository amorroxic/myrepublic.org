<?php

class CoolestController extends Site_Navigation_Default_Controller
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
    	$this->view->destinations = $this->destManager->getCoolestDestinations();
		$this->view->headTitle($this->view->t("Coolest destinations based on your opinion"));
		$this->view->headMeta()->appendName('description', 'Coolest destinations in the world highlighted on my*republic. A visual guide with beautiful images and real life opinions expressed by fellow travellers.');
		$this->view->headMeta()->appendName('keywords', 'photos photography travel opinions places destinations guide world countries');

    }
}
