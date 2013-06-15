<?php

class Auth {
	
	public function getAuthStatus() {

		$return = array();
		$return["status"] = Authentication_Manager::isAllowed();
		$return["message"] = Zend_Session::getId();
		return $return;

	}
	
	
	public function perform($params) {
	
		$username = $params["user"];
		$password = $params["pass"];
		
		$options = array();
		$options["user"] = $username;
		$options["pass"] = $password;
		$options["table"] = "site_users";
		
		Authentication_Manager::perform($options);
		
		$return = array();
		$return["status"] = Authentication_Manager::isAllowed();
		$return["message"] = Authentication_Manager::getErrorMessage();
		
		return $return;;
	
	}
	
	public function logout() {

		Authentication_Manager::logout();
		return Authentication_Manager::isAllowed();

	}
	

}

?>

