<?php
class FileUtil {

	var $isOutsideWebroot;
	var $downloadFrom;

	public function FileUtil() {
		$this->isOutsideWebroot = false;
		$this->baseDownloadPath = $_SERVER["DOCUMENT_ROOT"];
		$this->downloadFrom = "";
	}

	public function isOutside() {
		$this->isOutsideWebroot = true;
		$downloadPathArray = explode("/",$this->baseDownloadPath);
		array_pop($downloadPathArray);
		$this->baseDownloadPath = implode("/",$downloadPathArray);
	}

	public function setDownloadPath($downloadPath = array()) {
		$this->downloadFrom = implode("/",$downloadPath);
	}
	
	public function isValidLightroomFolder($folderName) {

		if (@is_dir($folderName)) {

			$root = opendir($folderName);

			while($file = readdir($root)){
				if (is_file($folderName . "/" .$file) && $this->isFileType($file, "jpg")) {
					@closedir($root);
					return true;
				}
			}

			@closedir($root);
			
		}
		
		return false;
	}
	
	public function getLightroomImages() {
	
		$lrImages = array();
		$relativePath = $this->baseDownloadPath. "/" . $this->downloadFrom . "/";
		$lightroomFolders = array("content","bin","images","large");
		$lightroomPath = @implode("/",$lightroomFolders);
		$imagesPath = $relativePath .$lightroomPath;
		if (@is_dir($imagesPath)) {

			$root = opendir($imagesPath);

			while($file = readdir($root)){
				if (is_file($imagesPath . "/" .$file) && $this->isFileType($file, "jpg")) {
					$lrImages[] = $imagesPath . "/" .$file;
				}
			}

			@closedir($root);
			
		}
		
		return $lrImages;
		
	}
	
	public function getValidLightroomFolders() {
		$lrFolders = array();
		$relativePath = $this->baseDownloadPath. "/" . $this->downloadFrom . "/";
		$folders = $this->getTopLevelFolders();
		$lightroomFolders = array("content","bin","images","large");
		$lightroomPath = @implode("/",$lightroomFolders);
		foreach ($folders as $folder) {
			$lrTestFolder = $folder ."/". $lightroomPath;
			if ($this->isValidLightroomFolder($lrTestFolder)) {
				$lrFolders[] = str_replace($relativePath,"",$folder);
			}
		}
		return $lrFolders;
	}
	
	public function getRelativeTopLevelFolders() {
		$folders = $this->getTopLevelFolders();
		$relativePath = $this->baseDownloadPath. "/" . $this->downloadFrom . "/";
		$relativeFolders = array();
		foreach ($folders as $folder) {
			$relativeFolders[] = str_replace($relativePath,"",$folder);
		}
		return $relativeFolders;
	}

	public function getTopLevelFolders() {
	
		$downloadPath = $this->baseDownloadPath. "/" . $this->downloadFrom;

		$filelist = array();
		$root = opendir($downloadPath);
		$dirs = array();

		while($file = readdir($root)){

		    if (@is_dir($downloadPath . "/" . $file) && $file!="." && $file!=".." ){
		         $dirs[] = ($downloadPath . "/" . $file);
		    }
		}

		@closedir($root);
		
		return $dirs;
		
	}

	public function getFileList($fileType = "") {

    		$downloadPath = $this->baseDownloadPath. "/" . $this->downloadFrom;

			$filelist = array();
			$root = opendir($downloadPath);
			$dirs = array();

			while($file = readdir($root)){

			    if (@is_dir($downloadPath . "/" . $file) && $file!="." && $file!=".." ){
			         $dirs[] = ($downloadPath . "/" . $file);
			    } else {
					if (is_file($downloadPath . "/" .$file) && $this->isFileType($file, $fileType)) {
						$filelist[] = $downloadPath . "/" . $file;
					}
				}
			}

			@closedir($root);

			$i = 0;
		    while($i < count($dirs)){
		        $d = @opendir($dirs[$i]);
		        while($f = @readdir($d)){
					$fullPath = $dirs[$i]  . "/" .  $f;
		            if(@is_dir($fullPath) && $f!="." && $f!=".."){
		                 $dirs[] = $dirs[$i]  . "/" .  $f;
		            } else {
						if (@is_file($fullPath)  &&  $this->isFileType($f, $fileType) )	$filelist[] = $fullPath;
					}
		        }
		        @closedir($d);
		        $i = $i + 1;
		    }
			$i = $i - 1;
			return $filelist;						
	}

	public function getFileListRelative($fileType = "") {
		$files = $this->getFileList($fileType);
		$relativePath = $this->baseDownloadPath. "/" . $this->downloadFrom . "/";
		$relativeFiles = array();
		foreach ($files as $file) {
			$relativeFiles[] = str_replace($relativePath,"",$file);
		}
		return $relativeFiles;
	}
	
	public function getFilePath($fileName) {
		$file = $this->baseDownloadPath. "/" . $this->downloadFrom . "/" . $fileName;
		
		if (file_exists($file)) {
			return $file;
		} else {
			return "";
		}
	}
	
	public function getFileExtension($fileName) {
		return strtolower(substr(strrchr($fileName,"."),1));
	}

	public function getFileName($fileName) {
		return strtolower(substr(strrchr($fileName,"/"),1));
	}

	
	public function isFileType($fileName, $extension) {
		if ($extension == "") return true;
		$fileExtension = $this->getFileExtension($fileName);
		if ($fileExtension == strtolower($extension)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function download($fileName) {
	
		ini_set('memory_limit','150M');
		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression'))
		  ini_set('zlib.output_compression', 'Off');

		// addition by Jorg Weske
		$file_extension = $this->getFileExtension($fileName);
		
		switch( $file_extension )
		{
		  case "pdf": $ctype="application/pdf"; break;
		  case "zip": $ctype="application/zip"; break;
		  case "doc": $ctype="application/msword"; break;
		  case "xls": $ctype="application/vnd.ms-excel"; break;
		  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		  case "gif": $ctype="image/gif"; break;
		  case "png": $ctype="image/png"; break;
		  case "jpeg":
		  case "jpg": $ctype="image/jpg"; break;
		}
		if (!isset($ctype)) throw new Zend_Server_Exception('Fisierul nu exista', 640);
		
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers 
		header("Content-Type: $ctype");
		// change, added quotes to allow spaces in filenames, by Rajkumar Singh
		header("Content-Disposition: attachment; filename=\"".basename($fileName)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($fileName));
		readfile($fileName);
		exit();
		
	}
	
	public function upload($destinationFolder="", $directories = array(), $appendTitle = "") {

		ini_set('memory_limit','50M');

		// strip any non-character
		for ($i=0; $i<count($directories); $i++) {
			$directories[$i] = ereg_replace("[^A-Za-z0-9]", "-", $directories[$i] );
		}
		$appendTitle = ereg_replace("[^A-Za-z0-9]", "-", $appendTitle );
		
		$filesUploaded = array();
		$i=0;
		foreach ($_FILES as $inputName => $fileProperties) {
			$i++;
			$fileNameWithoutExtension = $appendTitle."_".md5(time())."_".$i;
			
			if ($this->postFileIsValid($inputName)) {
				if ($destinationFolder != "") { 
					$uploadDestination = $this->baseDownloadPath . "/" . $destinationFolder;
				} else {
					$uploadDestination = $this->baseDownloadPath;
				}
				$pathToUpload = $this->createSubdirectories($uploadDestination,$directories);
				$fileExtension = $this->getFileExtension($fileProperties["name"]);
				$finalFileName = $fileNameWithoutExtension.".".$fileExtension;
				$isUploaded = true;
				$destinationFile = "";
				try {
					$destinationFile = $uploadDestination. $pathToUpload . "/" . $finalFileName;
					@move_uploaded_file($fileProperties['tmp_name'], $destinationFile);				
					@chmod($uploadDestination. $pathToUpload . "/" . $finalFileName, 0777);	
				} catch (Exception $e) {
					$isUploaded = false;
				}
				if (file_exists($destinationFile)) {
					if ($destinationFolder != "") {
						$filesUploaded[$inputName] = "/".$destinationFolder . $pathToUpload . "/" . $finalFileName;
					} else {
						$filesUploaded[$inputName] = $pathToUpload . "/" . $finalFileName;
					}					
				}
			}
			
		}
		
		return $filesUploaded;

		
	}
	
	public function postFileIsValid($inputName) {
	
		$maxUploadSize = 100 * 1024 * 1024;
		$fileProperties = $_FILES[$inputName];
		if ($fileProperties['size'] > $maxUploadSize) return false;

		if (is_array($fileProperties))
		{
			if ($fileProperties['name'] != "") {
				return true;
			} else {
				return false;				
			}
			
		} else {
			return false;
		}
		
	}

	public function createSubdirectories($where, $directories = array()) {
	
		$folderRelativePath = "";
		
		for ($i=0; $i<count($directories); $i++) {
			$folderRelativePath .= "/". $directories[$i];
			@mkdir($where . $folderRelativePath);
			@chmod($where . $folderRelativePath, 0777);
		}
		
		return $folderRelativePath;
		
	}
}
?>