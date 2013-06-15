<?php
class ImageUpload {
	
	//public variables
	public $path = '';
	public $errorStr = '';
	public $imgurl;

	//private variables
	private $fileProperties = array();
	private $_lang = array();
	
	private $_maxsize = 1048576;
	public $_im_status = false;

	private $fileNameWithoutExtension;
	private $uploadedFileType;
	
	private $subdirectories;
	
	private $uploadStatus;
	
	private $maxScaledWidth;
	private $maxScaledHeight;
	private $thumbWidth;
	private $thumbHeight;
	
	private $destinationMedium;
	private $destinationImages;
	private $destinationThumbs;
	private $fileRelativePath;
	private $fileCache;
	private $fileExtension;
	
	private $movieType;
	
	private $uploadMethod;

	//public methods
	public function __construct ($subdirs, $fileName = "file", $serverFiles = null)
	{

		$this->setup($subdirs);

		// strip any non-character
		for ($i=0; $i<count($this->subdirectories); $i++) {
			$this->subdirectories[$i] = strtolower(ereg_replace("[^A-Za-z0-9]", "-", $this->subdirectories[$i] ));
		}
		
		$fileName = ereg_replace("[^A-Za-z0-9]", "-", $fileName );
		
		$this->fileNameWithoutExtension = $fileName."_".md5(time());
		
		$fileCount = 0;
		$originalFileWithoutExtension = $this->fileNameWithoutExtension;
		$this->_createFolders();
		
		if (isset($serverFiles)) {
			$fileProcessingQueue = $this->disguiseLocalFilesAsUpload($serverFiles);
			$this->uploadMethod="copy";
		} else {
			$fileProcessingQueue = $_FILES;
			$this->uploadMethod="upload";
		}
		
		foreach ($fileProcessingQueue as $imageName => $fileDetails) {
		
			$fileCount++;
			$this->fileNameWithoutExtension .= "_".$fileCount;
		
			if (is_array($fileDetails))
			{
				$this->fileProperties = $fileDetails;
				$this->fileProperties["input"] = $imageName;
				
				if ($this->fileProperties['name'] == "") {
					$this->fileStatus("",false,$this->_lang['E_FILE']);
				} else {
					$this->_doUpload();
					$this->fileNameWithoutExtension = $originalFileWithoutExtension;				
				}
				
			} else {
			
				$this->fileStatus("",false,$this->_lang['E_FILE']);
				
			}
			
		
		}

	}
	
	private function disguiseLocalFilesAsUpload($fileList) {
		
		$outputFiles = array();
		
		$count = 0;
		foreach ($fileList as $file) {
			$count++;
			$newFile = array();
			$filePath = explode("/",$file);
			$newFile["name"] = $filePath[count($filePath)-1];	
			$newFile["tmp_name"] = $file;
			$newFile["size"] = filesize($file);
			$outputFiles["image_".$count] = $newFile;
		}
		return $outputFiles;
	}
	
	public function setup($subdirs) {

		$this->subdirectories = $subdirs;
		$this->uploadStatus = array();
		
		$this->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png');
		
		$lang['E_TYPE'] = 'Nu ati trimis o imagine';
		$lang['E_SIZE'] = 'Marimea fisierului depaseste 6MB';
		$lang['E_FILE'] = 'Nu s-a selectat nici o imagine';
		
		$this->_lang = $lang;
		
		$this->destinationImages = '/upload/images';
		$this->destinationThumbs = '/upload/thumbs';
		$this->destinationMedium = '/upload/medium';
		$this->fileCache		 = '/upload/cache';
		$this->fileRelativePath  = '';
		
		$this->_maxsize = 6 * 1024 * 1024;
				
		$this->maxScaledWidth = 600;
		$this->maxScaledHeight = 400;

		$this->thumbWidth = 80;
		$this->thumbHeight = 60;
		
		$this->movieType = "";
		
	}
	
	public function allowTypes ()
	{
		$str = '';
		if (count($this->allowedExtensions) > 0) {
			$str = 'Allowed types: (';
			$str .= implode(', ', $this->allowedExtensions);
			$str .= ')';
		}

		return $str;
	}

	private function _createFolders() {
	
		for ($i=0; $i<count($this->subdirectories); $i++) {
			$this->fileRelativePath .= "/". $this->subdirectories[$i];
			@mkdir($_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath);
			@mkdir($_SERVER["DOCUMENT_ROOT"] . $this->destinationThumbs . $this->fileRelativePath);
			@mkdir($_SERVER["DOCUMENT_ROOT"] . $this->destinationMedium . $this->fileRelativePath);
			
			@chmod($_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath, 0777);
			@chmod($_SERVER["DOCUMENT_ROOT"] . $this->destinationThumbs . $this->fileRelativePath, 0777);
			@chmod($_SERVER["DOCUMENT_ROOT"] . $this->destinationMedium . $this->fileRelativePath, 0777);
		}

	}

	private function _doUpload ()
	{
		
		$allowedExtension = $this->extensionIsAllowed($this->fileProperties['name']);
		$this->fileExtension = $this->getFileExtension($this->fileProperties['name']);

		if ($this->fileExtension == "swf") {
			$this->movieType = "Flash";
		} else {
			$this->movieType = "Image";					
		}
						
		if($allowedExtension)
		{
			if ($this->fileProperties['size'] > $this->_maxsize) {
				$this->fileStatus($this->fileProperties['name'],false,$this->_lang['E_SIZE']);
				return;								
			}

			$this->finalFileName = $this->fileNameWithoutExtension.".".$this->fileExtension;
			if ($this->uploadMethod == "upload") {
				@move_uploaded_file($this->fileProperties['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath . "/" . $this->finalFileName);
			} else {
				@copy($this->fileProperties['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath . "/" . $this->finalFileName);
			}

			@chmod($_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath . "/" . $this->finalFileName, 0777);
			
			
			$this->fileStatus($this->destinationImages . $this->fileRelativePath . "/" . $this->finalFileName,true,"");											
			

		}
		else
			$this->fileStatus($this->fileProperties['name'],false,$this->_lang['E_TYPE']);											
	}


	
	function fileStatus($filename="",$status=false,$message="",$originalFile="") {
		
		$fileStatus = array();
		if ($originalFile == "") $originalFile = $filename;
		
		if ($status) {
			$fileStatus["file"] = $filename;
			$fileStatus["image_path"] 	=  $_SERVER["DOCUMENT_ROOT"] . $this->destinationImages . $this->fileRelativePath . "/";
			$fileStatus["thumb_path"] 	=  $_SERVER["DOCUMENT_ROOT"] . $this->destinationThumbs . $this->fileRelativePath . "/";
			$fileStatus["medium_path"] 	=  $_SERVER["DOCUMENT_ROOT"] . $this->destinationMedium . $this->fileRelativePath . "/";
			$fileStatus["image_url"] 	=  $this->destinationImages . $this->fileRelativePath . "/";
			$fileStatus["thumb_url"] 	=  $this->destinationThumbs . $this->fileRelativePath . "/";
			$fileStatus["medium_url"] 	=  $this->destinationMedium . $this->fileRelativePath . "/";
			$fileStatus["file_no_extension"] = $this->fileNameWithoutExtension;
			$fileStatus["extension"] = $this->fileExtension;
		} else {
			$fileStatus["file"] = $this->destinationImages. "/" . "default/nophoto.jpg";
		}
		
		$fileStatus["bannertype"] 	= $this->movieType;
		$fileStatus["status"] 		= $status;
		$fileStatus["message"] 		= $message;
		$fileStatus["originalfile"] = $originalFile;
		
		$fileStatus["input"] = $this->fileProperties["input"];
		
		$this->uploadStatus[] = $fileStatus;
		
	}
	
	function extensionIsAllowed($filename = "") {
		
		preg_match("/\.([^\.]+)$/", $filename, $matches);
		if (count($matches)>1) {
			return in_array(strtolower($matches[1]), $this->allowedExtensions);			
		} else {
			return false;
		}
	}

	function getFileExtension($filename = "") {
		
		preg_match("/\.([^\.]+)$/", $filename, $matches);    
		if (count($matches)>1) {
			return strtolower($matches[1]);			
		} else {
			return "";
		}
	}

	function removeExtension($filename) {
	  return preg_replace('/(.+)\..*$/', '$1', $filename);
	}

	
	function getUploadStatus()
	{
		return $this->uploadStatus;	
	}

}

?>