<?php

class ContactController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;

	public function init() {
		parent::init();
		$this->destManager = new Destinations_Manager();
	}

    public function indexAction()
    {
    	$countries = $this->destManager->getCountriesWithPlaces();
    	$this->view->countries = $countries;
		$this->view->headTitle($this->view->t("Contact us"));
    }
}
