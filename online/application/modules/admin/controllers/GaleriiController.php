<?php

class Admin_GaleriiController extends Zend_Controller_Action
{

	public $galleryManager;
	public $photosManager;

    function preDispatch()
    {
        if (!Auth::isAdminAllowed()) {
            $this->_redirect('/admin/auth/login');
            return;
        }

    }
		
	public function init() 
	{ 
    	// Set the layout
    	$this->_helper->layout->setLayout('page');	
    		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('perform', 'json');
		$ajaxContext->initContext();
		
		$this->galleryManager = new Galleries_Manager();
		$this->photosManager = new Galleries_Photos_Manager();				
				
	}
		
    public function indexAction() {

		$this->view->area = "detalii";
		$this->view->headerText = "Galerii foto";
		$request = $this->getRequest();    	
		$this->view->galleries = $this->galleryManager->getGalerii();
		
		$gid = $request->getParam("id","");
		if ($gid == "") {
			if (count($this->view->galleries) > 0) {
				$gid = $this->view->galleries[0]["id"];
			}
		}
		
		$activeGallery = $this->galleryManager->getGalerii($gid);
		$this->view->photos = $this->photosManager->getPhotos($gid);
		if (count($activeGallery) > 0) $this->view->activeGallery = $activeGallery[0];
    	
    }
    
    public function adaugaAction() {
		$request = $this->getRequest();    	
		$galleryName = $request->getParam("gallery-name","");
		$gid = $this->galleryManager->getNewGalleryID($galleryName);
		$this->_redirect("/admin/galerii/");
    }
    
    public function editAction() {
		$request = $this->getRequest();    	
		$galleryName = $request->getParam("gallery-name-edit","");
		$gid = $request->getParam("id","");
		$params = array(
			"title" => $galleryName
		);
		$where = "id = ".$gid;
		$this->galleryManager->table->update($params,$where);
		$this->_redirect("/admin/galerii/index/id/".$gid);
    }
    
    public function deleteAction() {
		$request = $this->getRequest();    	
		$gid = $request->getParam("id","");
		$where = "id = ".$gid;
		$this->galleryManager->table->delete($where);
		$this->_redirect("/admin/galerii/");
    }
    
    public function importAction() {
    
		$this->view->area = "import";
		$this->view->headerText = "Import fotografii";
		$request = $this->getRequest();    	
		$this->view->galleries = $this->galleryManager->getGalerii();
    
		$gid = $request->getParam("id","");
		if ($gid == "") {
			if (count($this->view->galleries) > 0) {
				$gid = $this->view->galleries[0]["id"];
			}
		}
		
		$activeGallery = $this->galleryManager->getGalerii($gid);
		if (count($activeGallery) > 0) $this->view->activeGallery = $activeGallery[0];
    
		$fileUtil = new FileUtil();
		$fileUtil->isOutside();
		$downloadFolder = array("resurse","lightroom");
		$fileUtil->setDownloadPath($downloadFolder);
		$this->view->lightroomFolders = $fileUtil->getValidLightroomFolders();
        
    
    }
    
    public function processImportAction() {
		$this->view->area = "import";
		$this->view->headerText = "Import fotografii";
		$request = $this->getRequest();
		
		$galleryName = $request->getParam("gallery-name","lightroom");
		$galleryID = $request->getParam("gallery-id","0");
		
		$importType = $request->getParam("upload-selector","");
		switch ($importType) {
			case "upload":
						$directories = array('galleries',$galleryName);
						$image = new ImageUpload($directories,"file");
						break;
			case "lightroom":
						$folderName = $request->getParam("lightroom","");
						$fileUtil = new FileUtil();
						$fileUtil->isOutside();
						$downloadFolder = array("resurse","lightroom",$folderName);
						$fileUtil->setDownloadPath($downloadFolder);
						$files = $fileUtil->getLightroomImages();
						$directories = array('galleries',$galleryName);
						$image = new ImageUpload($directories,"file",$files);
						break;
		} 


		$uploadResult = $image->getUploadStatus();

		$photos = array();

		foreach ($uploadResult as $result) {

			if ($result["status"]) {
			
				$file = $result["file_path"]. $result["file_no_extension"].".".$result["extension"];
				$thumb = $result["thumb_path"] . $result["file_no_extension"].".".$result["extension"];
				$thumbFile = $_SERVER["DOCUMENT_ROOT"] . $thumb;
	
				$flashThumb = $result["thumb_path"] . $result["file_no_extension"]."_flash.".$result["extension"];
				$flashThumbFile = $_SERVER["DOCUMENT_ROOT"] . $flashThumb;
	
				$resizeOutput = ImageUtil::resizeImage($file, $thumbFile, $flashThumbFile);
				if ($resizeOutput) {
					$file = $result["file"];
					$photo = array();
					$photo["image"] = $file;
					$photo["thumb"] = $thumb;
					$photos[] = $photo;
				} else {
					$this->view->message = $this->t("Eroare la procesare imagini");
				}
			} else {
				$this->view->message = $result["message"];
			}
		

		}
		
		$this->view->galleryID = $galleryID; 
		$this->view->galleryName = $galleryName;
		$this->view->photos = $photos;  	
    	
    }
    
    public function saveImportAction() {
    
		$this->view->area = "import";
		$request = $this->getRequest();
		
		$imageCount = $request->getParam("image-count","0");
		$galleryID = $request->getParam("gallery-id","0");
		
		for ($i=0;$i<$imageCount;$i++) {
			$image = $request->getParam("image_".$i,"");
			$thumb = $request->getParam("thumb_".$i,"");
			$imageTitle = $request->getParam("title_".$i,"");
			$imageDescription = $request->getParam("description_".$i,"");
			$params = array(
				"photo" => $image,
				"thumb" => $thumb,
				"name"	=> $imageTitle,
				"description" => $imageDescription,
				"approved" => "1",
				"galerie_id" => $galleryID,
				"date" => date("Y-m-d")
			);
			$this->photosManager->table->insert($params);
		}
		$this->_redirect("/admin/galerii/index/id/".$galleryID);
    
    }
    
    public function stergeImagineAction() {

		$request = $this->getRequest();
		$galleryID = $request->getParam("gid","0");
		$imageID = $request->getParam("id","0");
		$photo = $this->photosManager->getPhoto($imageID);
		if (count($photo) > 0) {
			$photo = $photo[0];
			@unlink($_SERVER["DOCUMENT_ROOT"].$photo["photo"]);
			@unlink($_SERVER["DOCUMENT_ROOT"].$photo["thumb"]);
		}
		$where = "id = ".$imageID;
		$this->photosManager->table->delete($where);
		$this->_redirect("/admin/galerii/index/id/".$galleryID);
    
    }
    
    public function updatePhotosAction() {

		$this->view->area = "import";
		$request = $this->getRequest();
		
		$imageCount = $request->getParam("image-count","0");
		$galleryID = $request->getParam("gallery-id","0");
		
		for ($i=0;$i<$imageCount;$i++) {
			$imageID = $request->getParam("photo_".$i,"");
			$imageTitle = $request->getParam("title_".$i,"");
			$imageDescription = $request->getParam("description_".$i,"");
			$params = array(
				"name"	=> $imageTitle,
				"description" => $imageDescription
			);
			$where = "id = ".$imageID;
			$this->photosManager->table->update($params,$where);
		}
		$this->_redirect("/admin/galerii/index/id/".$galleryID);
    	
    }
    

    
}