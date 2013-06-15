<?php

class Users {
		
	
	public function register($params) {
		$userManager = new User_Manager();
		return $userManager->register($params);
	}

	public function sendPassword($params) {
		$userManager = new User_Manager();
		return $userManager->sendPassword($params);
	}
	
}

?>

