<?php

class PhotoService {
	
	public function getPhotosByDestination($params) {

		$countryName 	= $params["country"];
		$locationName 	= $params["location"];
	
		$destinationManager = new Destinations_Manager();
		$photoManager = new Photos_Manager();
		
		$countryID = null;
		if (isset($countryName)) {
			$country = $destinationManager->getDestination($countryName,0,false,false);
			if (count($country) > 0) $countryID = $country[0]["id"];
		}
		$locations = $destinationManager->getDestination($locationName,$countryID,true,true);
		
		$photos = array();
		
		foreach ($locations as $location) {
			$locationID = $location["id"];
			$locationPhotos = $photoManager->getPhotosByLocation($locationID);
			foreach ($locationPhotos as $locationPhoto) {
				$photo = array();
				foreach ($locationPhoto as $key=>$value) {
					$photo[$key] = $value;
				}
				$photo["hierarchy"] = $location["hierarchy"];
				$photo["destination"] = $location["name"];
				$photos[] = $photo;
			}
		}
		
		return $photos;
		
	}
	
	public function getPhotos($photoFilter) {

		//$filterArguments = $photoFilter[0];
		$filter = "random";//$filterArguments[0];
		$limit = "10";//$filterArguments[1];

		$userPhotoManager = new Photos_Manager();
		
		$filter = array();
		
	    if (is_object($photoFilter)) {
	        foreach ($photoFilter as $key => $value) {
	            
				switch (strtolower($key)) {
					case "filter":
								$filter = strtolower($value);
								break;
					case "id":
								$id = $value;
								break;
					case "limit":
								$limit = $value;
								break;
				}
	        }
	    }
	    else {

			foreach (array_keys($photoFilter) as $key) {
				switch (strtolower($key)) {
					case "filter":
								$filter = strtolower($photoFilter[$key]);
								break;
					case "id":
								$id = $photoFilter[$key];
								break;
					case "limit":
								$limit = $photoFilter[$key];
								break;
				}
			}

	    }
		
		
/*
		foreach (array_keys($photoFilter) as $key) {
			switch (strtolower($key)) {
				case "filter":
							$filter = strtolower($photoFilter[$key]);
							break;
				case "id":
							$id = $photoFilter[$key];
							break;
				case "limit":
							$limit = $photoFilter[$key];
							break;
			}
		}
*/
		
		
		$output = array();
		$output["banner"] = "";
		
		switch ($filter) {
		
			case "category":
					$photos = $userPhotoManager->getCategoryVotedPhotos($id,$limit);
					break;
			case "latest":
					$photos = $userPhotoManager->getLatestPhotos($limit);
					break;
			case "rank":
					$photos = $userPhotoManager->getRankedPhotos($limit);
					break;
			case "views":
					$photos = $userPhotoManager->getViewedPhotos($limit);
					break;
			case "comments":
					$photos = $userPhotoManager->getCommentedPhotos($limit);
					break;
			case "user":
			
			    	$userTable	= new User_Table();
					$select = $userTable->select();
					$select->where("id = ?", $id);
					$rows = $userTable->fetchAll($select)->toArray();
					if (count($rows) > 0) {
						$banner = $rows[0]["banner"];
					}
					$output["banner"] = $banner;
					$photos = $userPhotoManager->getUserPhotos($id, $limit);
					//$photos = $userPhotoManager->getLatestPhotos($limit);
					break;
			case "random":
					$photos = $userPhotoManager->getRandomPhotos($limit);
					break;
			default:
					$photos = $userPhotoManager->getRandomPhotos($limit);
					break;
		}
		
		for ($i=0; $i<count($photos);$i++) {
		
			$linkDescription = $photos[$i]["description"];
			if (strlen($linkDescription) > 50) $linkDescription = substr($linkDescription, 0, 50);
			$contentLink = "/galerie/fotografii/".ereg_replace("[^A-Za-z0-9]", "-", $linkDescription)."-".$photos[$i]["id"];
			$contentLink = strtolower($contentLink);
			//display only flash thumbnails in flash
			if ($photos[$i]["flash_thumb"] != "") $photos[$i]["photo"] = $photos[$i]["flash_thumb"];
			$photos[$i]["photo_link"] = $contentLink;
		}
		
		$output["photos"] = $photos;
		return $output;
	}

}

?>

