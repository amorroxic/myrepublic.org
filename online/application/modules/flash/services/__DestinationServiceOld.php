<?php

class DestinationService {

	public function getAllLocations() {
		$destinationManager = new Destinations_Manager();
		$locations = $destinationManager->getAllDestinations();
		return $locations;	
	}
	
	public function getCountry($params) {
		if (isset($params["country"])) $countryName 	= $params["country"];
		$destinationManager = new Destinations_Manager();
		if (isset($countryName)) {
			$locations = $destinationManager->getDestination($countryName,0,true,true);
		} else {
			$locations = array();
		}
		return $locations;
	}
	
	public function getDestinations($params) {

		if (isset($params["country"])) $countryName 	= $params["country"];
		if (isset($params["location"])) $locationName 	= $params["location"];
	
		$destinationManager = new Destinations_Manager();
		$photoManager = new Photos_Manager();
		
		$countryID = null;
		if (isset($countryName)) {
			$country = $destinationManager->getDestination($countryName,0,false,false);
			if (count($country) > 0) $countryID = $country[0]["id"];
		}
		if (isset($locationName)) {
			$locations = $destinationManager->getDestination($locationName,$countryID,true,true);
		} else {
			$locations = array();
		}
		
		return $locations;
		
	}

}

?>

