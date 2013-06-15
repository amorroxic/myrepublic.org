<?php

class MyAccountController extends Site_Navigation_Default_Controller
{

	private $destManager;
	private $photoManager;
	private $auth;

	public function init() {
		parent::init();
		$this->destManager = new Destinations_Manager();
		$this->photoManager = new Photos_Manager();

    	$countries = $this->destManager->getCountriesWithPlaces();
    	$this->view->countries = $countries;

		$this->auth = new Authentication_Manager();
    	
    	$allowed = array("login","incoming","register","registered", "recover", "password-sent", "change-password");
    	
    	$action =  strtolower($this->getRequest()->getActionName());
    	if (!in_array($action, $allowed) && !Authentication_Manager::isAllowed()) {
    		 $this->_redirect($this->view->l("my-account","login"));
    	}

	}

    public function indexAction()
    {
    	$this->_redirect($this->view->l("my-account","photos"));
    }
    
    public function logoutAction() {
    	Authentication_Manager::logout();
    	$this->_redirect($this->view->l());
    }
    
    public function photosAction() {
    	$me = Authentication_Manager::me();
    	if (isset($me)) {
    		$this->view->userID = $me->id;
	    	$this->view->myCountries = $this->destManager->getUserDestinations($me->id);
	    	return;
    	}
    	$this->_redirect($this->view->l("my-account","login"));
    }
    
    public function favoritesAction() {
    	$me = Authentication_Manager::me();
    	if (isset($me)) {
    		$this->view->userID = $me->id;
	    	$this->view->myCountries = $this->destManager->getUserFavorites($me->id);
	    	return;
    	}
    	$this->_redirect($this->view->l("my-account","login"));
    }
    
    public function loginAction() {

    	$request 	= $this->getRequest();
    	$isPost 	= $request->isPost();
    	
    	if ($isPost) {
    	
			Authentication_Manager::logout();
        	$options = array();
        	$options["user"] 	= $request->getPost('username');
        	$options["pass"] 	= $request->getPost('password');
        	$options["table"]	= "site_users";        
        	Authentication_Manager::perform($options);
			
			if (Authentication_Manager::isAllowed()) {
		            $this->_redirect($this->view->l("my-account","photos"));		
			} else {
            		$this->view->message = Authentication_Manager::getErrorMessage();
			}    	
    	}
		$this->view->headTitle("Login to upload photos and manage your favorite destinations");

    }
    
    public function registerAction() {

    	$request 	= $this->getRequest();
    	$isPost 	= $request->isPost();
    	
		if ($isPost) {

			$mail 		= $request->getParam('email',"");
			$pass 		= $request->getParam('password',"");
			$confirm 	= $request->getParam('confirm',"");
			$firstname 	= $request->getParam('firstname',"");
			$lastname 	= $request->getParam('lastname',"");
			$web 		= $request->getParam('website',"");

			$status = true;
			$message = "";

			$validator=new Zend_Validate_EmailAddress();
			if (!$validator->isValid($mail)) {
				$status = false;
				$message = $this->view->t("Adresa de mail nu este valida.");
			}

			if ($status) {

				$userTable = new User_Table();
	
				$select = $userTable->select();
				$select->where('email = ?', $mail);
				$rows = $userTable->fetchAll($select);
				
				if (count($rows) > 0) { 
					$status = false;
					$message = $this->view->t("Adresa de mail exista deja in sistem.");
				}

			}

			if ($status) {
				if ($pass == "") {
					$status = false;
					$message = $this->view->t("Alegeti o parola.");
				}
			}

			if ($status) {
				if ($pass != $confirm) {
					$status = false;
					$message = $this->view->t("Cele doua parole nu coincid.");
				}
			}


			if ($status) {
			
				Authentication_Manager::logout();
				
				$statement = array(
					'password'				=>md5($pass),
					'first_name'			=>$firstname,
					'last_name'				=>$lastname,
					'email'					=>$mail,
					'website'				=>$web
				);
				
				$userTable->insert($statement);
			
			}			
			
			if ($status) {
	    		 $this->_redirect($this->view->l("my-account","registered"));
	    		 return;
			} else {
				$this->view->message = $message;
			}
			
			$this->view->email = $mail;
			$this->view->website = $web;
			$this->view->firstname = $firstname;
			$this->view->lastname = $lastname;

		}    	
    	
    	
    }    
    public function registeredAction() {
    	
    }

	public function recoverAction() {
	
	
    	$request 	= $this->getRequest();
    	$isPost 	= $request->isPost();
    	$userTable	= new User_Table();
    	
    	$mail 		= $request->getParam("email",""); 
    	$code 		= $request->getParam("code",""); 
    	
    	if ($isPost) {
    	
			$status = true;
			$message = "";

			$validator=new Zend_Validate_EmailAddress();
			if (!$validator->isValid($mail)) {
				$status = false;
				$message = $this->view->t("Adresa de mail nu este valida.");
			}

			if ($status) {

				$userTable = new User_Table();
	
				$select = $userTable->select();
				$select->where('email = ?', $mail);
				$rows = $userTable->fetchAll($select);
				
				if (count($rows) == 0) { 
					$status = false;
					$message = $this->view->t("Adresa de mail nu exista in sistem.");
				}

			}
			
			if ($status) {
			
				$id 		= $rows[0]->id;
				$email 		= $rows[0]->email;
				$name 		= $rows[0]->first_name." ".$rows[0]->last_name;
				$username 	= $rows[0]->username;
				$code		= md5(time());
				
				$statement = array(
					'passreset'				=>$code
				);
				
				$where = "id = " . $id;
				$userTable->update($statement,$where);

				$transport = new Zend_Mail_Transport_Smtp('localhost');

				$mail = new Zend_Mail('utf-8');
				
				$mailText = $this->view->t("Salut ").$name.",";
				$mailText .= "\n\n";
				$mailText .= $this->view->t("Tu (sau o terta persoana ce a introdus adresa ta de email) a solicitat schimbarea parolei de acces pe website-ul nostru.");
				$mailText .= $this->view->t(" Daca nu ai solicitat acest lucru nu este nevoie sa faci nimic. Datele tale sunt in siguranta si parola ta este neschimbata.");
				$mailText .= "\n\n";
				$mailText .= $this->view->t("Daca ai solicitat serviciul copiaza acest link in adresa browser-ului tau:\n");
				$mailText .= "http://".$_SERVER['HTTP_HOST'].$this->view->l("my-account","change-password")."?mail=".$email."&code=".$code."\n\n";
				
				$mail->setBodyText($mailText);
				$mail->setFrom('system@myrepublic.org', $this->view->t('my*republic'));
				$mail->addTo($email, $name);
				$mail->setSubject($this->view->t('Recuperare parola'));
				try {
					$mail->send($transport);
				}
				catch (Exception $ex) {
					// do nothing
				}
				
			}

			if ($status) {
	    		 $this->_redirect($this->view->l("my-account","password-sent"));
	    		 return;
			} else {
				$this->view->message = $message;
			}
    	    	
    	}
	
	}
	
	public function passwordSentAction() {
	
	}
	
    public function changePasswordAction() {
    
    	$request 	= $this->getRequest();
    	$isPost 	= $request->isPost();
    	$userTable	= new User_Table();

		$mail 		= $request->getParam("mail",""); 
		$code 		= $request->getParam("code",""); 

    	$this->view->mail = $mail;
    	$this->view->code = $code;

    	
    	if ($isPost) {

			$pass 		= $request->getParam('password',"");
			$confirm 	= $request->getParam('confirm',"");
	
			$status 	= true;

			$validator=new Zend_Validate_EmailAddress();
			if (!$validator->isValid($mail)) {
				$status = false;
				$message = $this->view->t("Adresa de mail nu este valida.");
			}

			if ($status) {

				$userTable = new User_Table();
	
				$select = $userTable->select();
				$select->where('email = ?', $mail);
				$rows = $userTable->fetchAll($select);
				
				if (count($rows) <= 0) { 
					$status = false;
					$message = $this->view->t("Adresa de mail nu exista in sistem.");
				}

			}

			if ($status) {
				if ($pass == "") {
					$status = false;
					$message = $this->view->t("Alegeti o parola.");
				}
			}

			if ($status) {
				if ($pass != $confirm) {
					$status = false;
					$message = $this->view->t("Cele doua parole nu coincid.");
				}
			}

			if ($status) {
				$select = $userTable->select();
				$select->where('email = ?', $mail);
				$select->where('passreset = ?', $code);
				$rows = $userTable->fetchAll($select);
				if (count($rows) != 1) {
					$status = false;
					$message = $this->view->t("Codul este invalid.");	
				}
			}
			
			if ($status) {
					
					$id = $rows[0]->id;
					
					$statement = array(
						'password'				=>md5($pass),
						'passreset'				=>""
					);
					
					$where = "id = " . $id;
					$userTable->update($statement,$where);
					$message = $this->view->t("Modificarile au fost efectuate");
	
			}
			
			if ($status) {
				$this->render("changed-password");
				return;
			} else {
				$this->view->message = $message;
			}
			
    	}
    	
    
    }	

	public function incomingAction() {
		
		$request 					= $this->getRequest();
		$sessionID = $request->getParam("s","");
		if ($sessionID != "") Zend_Session::setId($sessionID);

	    $this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);		
		$this->me = Authentication_Manager::me();

		$output = array();

		if (!$this->me) {
			$output["status"] = false;
			$output["message"] = "You need to be logged on.";
			echo json_encode($output);
			return;
		}
		
		$status = $this->executeUpload();
		
		if (!isset($status)) {
			$output["status"] = false;
			$output["message"] = "There was a problem with this file.";
		} else {
			if (!$status["result"]) {
				$output["status"] = false;
				$output["message"] = $status["message"];
			} else {
				$output["status"] = true;
				$output["message"] = "Done.";
			}
		}
		echo json_encode($output);
				
	}
    
	public function executeUpload() {

		$request 					= $this->getRequest();
		$location	 				= $request->getParam("destination","");
		$countryID	 				= $request->getParam("country","");

		$userData = Zend_Auth::getInstance()->getStorage()->read();

		if ($location == "") 			return array("result"=>false,"message"=>$this->view->t("No location specified."));
		if ($countryID == "") 			return array("result"=>false,"message"=>$this->view->t("No country specified."));
		if (!is_numeric($countryID))	return array("result"=>false,"message"=>$this->view->t("The country you specified does not exist."));
		if (!isset($userData)) 			return array("result"=>false,"message"=>$this->view->t("You are not logged on."));
		if (!$request->isPost()) 		return array("result"=>false,"message"=>$this->view->t("Hack attempt. Logged."));

		$destinations = new Destinations_Manager();
		
		$country = $destinations->getCountryWithoutInfo($countryID);
		if (count($country) == 0) return array("result"=>false,"message"=>$this->view->t("No country specified."));
		
		$id = $destinations->addDestination($location,$countryID);
		if ($id == -1) return array("result"=>false,"message"=>$this->view->t("No location specified."));
		
		$locationFolder = ereg_replace("[^A-Za-z0-9]", "_", $location );
		$countryFolder 	= ereg_replace("[^A-Za-z0-9]", "_", $country[0]["name"] );

		$fileAppend = $userData->id ."_". ereg_replace("[^A-Za-z0-9]", "", $userData->username);
		$directories = array();
		$directories[] = $countryFolder;
		$directories[] = $locationFolder;
		$image = new ImageUpload($directories, $fileAppend);
		$result = $image->getUploadStatus();

		if (count($result) == 0) return array("result"=>false,"message"=>$this->view->t("Could not get upload status."));
		$result = $result[0];
		if (!$result["status"]) return array("result"=>false,"message"=>$this->view->t("There was an error while uploading the file."));

		$absoluteImage 		= $result["image_path"].$result["file_no_extension"].".".$result["extension"];
		$absoluteMedium 	= $result["medium_path"].$result["file_no_extension"].".".$result["extension"];		
		$absoluteThumb 		= $result["thumb_path"].$result["file_no_extension"].".".$result["extension"];

		$resizeOutput = ImageUtil::setMagickMaximumSize($absoluteImage, $absoluteMedium, 640, 480);
		if (!$resizeOutput) {
			@unlink($absoluteImage);
			return array("result"=>false,"message"=>$this->view->t("Image is invalid or too big. Maximum resolution accepted is 6 megapixels (3000 x 2008 px)"));
		}
		
		$midWidth = $resizeOutput["width"];
		$midHeight = $resizeOutput["height"];
		
		$resizeOutput = ImageUtil::setMagickMaximumSize($absoluteImage, $absoluteThumb, 160, 120);
		if (!$resizeOutput) {
			@unlink($absoluteImage);
			@unlink($absoluteMedium);
			return array("result"=>false,"message"=>$this->view->t("Image is invalid or too big. Maximum resolution accepted is 6 megapixels (3000 x 2008 px)"));
		}
		$thuWidth = $resizeOutput["width"];
		$thuHeight = $resizeOutput["height"];
		
		$resizeOutput = ImageUtil::setMagickMaximumSize($absoluteImage, $absoluteImage, 1366, 1024);
		if (!$resizeOutput) {
			@unlink($absoluteImage);
			@unlink($absoluteMedium);
			@unlink($absoluteThumb);
			return array("result"=>false,"message"=>$this->view->t("Image is invalid or too big. Maximum resolution accepted is 6 megapixels (3000 x 2008 px)"));
		}
		$imaWidth = $resizeOutput["width"];
		$imaHeight = $resizeOutput["height"];
		$exif = $resizeOutput["exif"];
		

		$fotografii = new Photos_Table();
		$statement = array(
			'photo'			=>$result["image_url"].$result["file_no_extension"].".".$result["extension"],
			'thumb' 		=>$result["thumb_url"].$result["file_no_extension"].".".$result["extension"],
			'medium' 		=>$result["medium_url"].$result["file_no_extension"].".".$result["extension"],
			'medium_width'	=>$midWidth,
			'medium_height'	=>$midHeight,
			'thumb_width'	=>$thuWidth,
			'thumb_height'	=>$thuHeight,
			'width'			=>$imaWidth,
			'height'		=>$imaHeight,
			'additional_fields'	=>$exif,
			'user_id'		=>$userData->id,
			'destination_id'=>$id,
			'published'		=>date("Y-m-d"),
			'approved'		=>"0"
		);
		$fotografii->insert($statement);

		return array("result"=>true,"message"=>$this->view->t("Image is online."));

	}
    
    public function uploadAction() {
    
		// Set the site title
		$this->view->sessionID = Zend_Session::getId();
		$this->view->headTitle($this->view->t("upload photos"));
		
		$this->view->allCountries = $this->destManager->getCountriesWithoutPlaces();
    
    
    }
    
    public function setsAction() {

    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
		$this->view->headTitle($this->view->t("My sets"));
		
        $photoManager 				= new Photos_Manager();    	
		$setManager 				= new Sets_Manager();
		
		$photoManager = new Photos_Manager();
		$setPhotos = $photoManager->getPhotosBySet("33");
		
		$this->view->allCountries 	= $this->destManager->getCountriesWithoutPlaces();
		$this->view->sets 			= $setManager->getSetsByUser($me->id);
    	$this->view->destinations 	= $this->destManager->getDestinationsWithCountries();
		
		$locationID 				= "set";
		$this->view->locationID		= $locationID;
		
		$setID					= -1;
		if (count($this->view->sets) > 0) $setID = $this->view->sets[0]["id"];
		$this->view->setID			= $setID;

        $this->view->photos 		= $photoManager->getUserPhotosByLocation($me->id,$locationID);
        $setPhotos					= $setManager->getPhotoIDSFromSet($setID);
        
        for ($i = count($this->view->photos)-1; $i>=0; $i--) {
        	if (in_array($this->view->photos[$i]["id"],$setPhotos)) {
        		$this->view->photos[$i]["added"] = "1";
        	} else {
        		if ($locationID == "set") {
        			array_splice($this->view->photos,$i,1);
        		} else {
	        		$this->view->photos[$i]["added"] = "0";
        		}
        	}
        }
        
    }
    
	public function addSetAction() {
	
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
    	
    	if ($isPost && $isAjax) {

			$setName 			= $request->getParam("set-name","");
			$locationID 		= "set";
			$setManager 		= new Sets_Manager();    		
    		$setID = $setManager->add($setName,$me->id);
    		
			$this->view->sets = $setManager->getSetsByUser($me->id);
	    	$this->view->locationID		= $locationID;
	    	$this->view->setID			= $setID;
	    	$this->view->photos		= array();
    		
			$jsonData = array();
			$jsonData['sets'] = $this->view->render('my-account/sets-inner.phtml');
			$this->_helper->json->sendJson($jsonData);
			return;
			
		} else {
		
	        $this->_redirect("/ro/my-account");
    		return;
    		
		}	
	
	}
    
    
	public function deleteSetAction() {
	
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
    	
    	if ($isPost && $isAjax) {

			$locationID 		= "set";
			$setID 				= $request->getParam("set-id","");
			$setManager 		= new Sets_Manager();    		
    		$setManager->deleteByID($setID);
    		
			$this->view->sets = $setManager->getSetsByUser($me->id);

	    	$photoManager				= new Photos_Manager();

			$setID					= -1;
			if (count($this->view->sets) > 0) $setID = $this->view->sets[0]["id"];

	        $this->view->photos 		= $photoManager->getUserPhotosByLocation($me->id,$locationID);
	    	$this->view->locationID		= $locationID;
		    $this->view->setID			= $setID;
	        $setPhotos					= $setManager->getPhotoIDSFromSet($setID);
	        
	        for ($i = count($this->view->photos)-1; $i>=0; $i--) {
	        	if (in_array($this->view->photos[$i]["id"],$setPhotos)) {
	        		$this->view->photos[$i]["added"] = "1";
	        	} else {
	        	 	array_splice($this->view->photos,$i,1);
	        	}
	        }
    		
			$jsonData = array();
			$jsonData['sets'] = $this->view->render('my-account/sets-inner.phtml');
			$this->_helper->json->sendJson($jsonData);
			return;
			
		} else {
		
	        $this->_redirect("/ro/my-account");
    		return;
    		
		}	
	
	}    
    
    
	public function photosetAction() {
	
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
    	
    	if ($isPost && $isAjax) {

			$setID 		= $request->getParam("set-id","");
			$photoID 	= $request->getParam("photo-id","");
			$oper 		= $request->getParam("operation","");
			$setManager = new Sets_Manager();
			switch ($oper) {
				case "remove":
					    		$setManager->removePhotoFromSet($photoID,$setID);
								break;
				case "add":
					    		$setManager->assignPhotoToSet($photoID,$setID);
								break;
			}
    		
			$jsonData = array();
			$jsonData['status'] = "ok";
			$this->_helper->json->sendJson($jsonData);
			return;
			
		} else {
		
	        $this->_redirect("/ro/my-account");
    		return;
    		
		}	
	
	}
	
	public function filterSetPhotosAction() {
	
    	$request 	= $this->getRequest();
    	$isAjax = $request->isXmlHttpRequest();
    	$isPost = $request->isPost();
    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
    	
    	if ($isPost && $isAjax) {

	        $photoManager 				= new Photos_Manager();    	
			$setManager 				= new Sets_Manager();

			$setID 			= $request->getParam("set-id","");
			$locationID 	= $request->getParam("location-id","");

	        $this->view->photos 		= $photoManager->getUserPhotosByLocation($me->id,$locationID);
	        $setPhotos					= $setManager->getPhotoIDSFromSet($setID);

	        for ($i = count($this->view->photos)-1; $i>=0; $i--) {
	        	if (in_array($this->view->photos[$i]["id"],$setPhotos)) {
	        		$this->view->photos[$i]["added"] = "1";
	        	} else {
	        		if ($locationID == "set") {
	        			array_splice($this->view->photos,$i,1);
	        		} else {
		        		$this->view->photos[$i]["added"] = "0";
	        		}
	        	}
	        }

			$jsonData = array();
			$jsonData['photos'] = $this->view->render('my-account/photos/set-photos.phtml');
			$this->_helper->json->sendJson($jsonData);
			return;

			
		} else {
		
	        $this->_redirect("/ro/my-account");
    		return;
    		
		}	
	
		
	}
	
	public function filterAction() {
	
	
    	$request 	= $this->getRequest();
    	$me = Authentication_Manager::me();
    	if (!isset($me)) $this->_redirect($this->view->l("my-account","login"));
    	
        $photoManager 				= new Photos_Manager();    	
		$setManager 				= new Sets_Manager();

		$setID 			= $request->getParam("set-id","");
		$locationID 	= $request->getParam("location-id","");

        $this->view->photos 		= $photoManager->getUserPhotosByLocation($me->id,$locationID);
        $setPhotos					= $setManager->getPhotoIDSFromSet($setID);

        for ($i = count($this->view->photos)-1; $i>=0; $i--) {
        	if (in_array($this->view->photos[$i]["id"],$setPhotos)) {
        		$this->view->photos[$i]["added"] = "1";
        	} else {
        		if ($locationID == "set") {
        			array_splice($this->view->photos,$i,1);
        		} else {
	        		$this->view->photos[$i]["added"] = "0";
        		}
        	}
        }

	
	
	}
	     
    
}