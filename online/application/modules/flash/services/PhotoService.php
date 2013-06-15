<?php

class PhotoService {
	
	public function getPhotosByDestination($parameters) {
	
		$destinationID = $parameters["id"];
		$favUserID = $parameters["favuser"];
		$photoManager = new Photos_Manager();
		if (isset($favUserID)) {
			$locationPhotos = $photoManager->getPhotosByLocationAndFavoritedBy($destinationID,$favUserID);
		} else {
			$locationPhotos = $photoManager->getPhotosByLocation($destinationID);
		}
		return $locationPhotos;
		
	}


	public function getPhotosBySet($parameters) {
	
		$setID = $parameters["id"];
		$photoManager = new Photos_Manager();
		$setPhotos = $photoManager->getPhotosBySet($setID);
		return $setPhotos;
		
	}
}

?>

