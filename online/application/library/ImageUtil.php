<?php
	class ImageUtil {
	
	
		public static function setMagickMaximumSize($srcImage, $destImage, $minWidth, $minHeight) {
		
			try {

				if (file_exists($srcImage)) {

					$er = new phpExifReader($srcImage);
					$er->processFile();
					$imageInfo = $er->getImageInfo();

			        $exifArray = array();
			        if (isset($imageInfo['make'])) $exifArray["Camera"] = $imageInfo['make'];
			        if (isset($imageInfo['model'])) $exifArray["Model"] = $imageInfo['model'];
			        if (isset($imageInfo['focalLength'])) $exifArray["Focal distance"] = $imageInfo['focalLength'];
			        
			        if (isset($imageInfo['fnumber'])) {
			        	 $exifArray["Diafragma"] = $imageInfo['fnumber'];
			        } else {
				        if (isset($imageInfo['aperture'])) $exifArray["Aperture"] = $imageInfo['aperture'];
			        }
			        if (isset($imageInfo['exposureTime'])) $exifArray["Shutter speed"] = $imageInfo['exposureTime'];
			        if (isset($imageInfo['isoEquiv'])) $exifArray["ISO"] = $imageInfo['isoEquiv'];
			        if (isset($imageInfo['DateTime'])) $exifArray["Date"] = $imageInfo['DateTime'];


					$im = new Imagick( $srcImage );
					$im->resizeImage($minWidth, $minHeight, imagick::FILTER_LANCZOS, 1, true);
					$destinationWidth = $im->getImageWidth();
					$destinationHeight = $im->getImageHeight();
					$im->writeImage( $destImage );

					chmod($destImage, 0777);
					
					$returnValues = array();
					$returnValues["image"] = $destImage;
					$returnValues["width"] = $destinationWidth;
					$returnValues["height"] = $destinationHeight;
					$returnValues["exif"] = urlencode(serialize($exifArray));
					return $returnValues;					
				
				} else {
					@unlink($destImage);
					return false;
				}				
		
				
			} catch (Exception $e) {
				return false;
			}
		
		
		} 	
	
		public static function setMaximumSize($srcImage, $destImage, $minWidth, $minHeight) {
		
			$destinationRatio = $minWidth / $minHeight;

			$maxWidth = 3010;
			$maxHeight = 2010;

			ini_set('gd.jpeg_ignore_warning', 1);
			error_reporting (E_ALL ^ E_NOTICE);
			
			try {
			
				$result = getimagesize($srcImage);

				if ($result) {
								
					list($imagewidth, $imageheight, $imageType) = $result;
					
					if ($imagewidth > $maxWidth || $imageheight > $maxHeight) return false;
					
					$imageType = image_type_to_mime_type($imageType);
					
					$sourceAspectRatio = $imagewidth / $imageheight;
					
					if ($sourceAspectRatio > $destinationRatio) {
						$destinationWidth 	= $minWidth;
						$destinationHeight 	= ($destinationWidth / $sourceAspectRatio);
					} else {
						$destinationHeight 	= $minHeight;
						$destinationWidth 	= $destinationHeight * $sourceAspectRatio;
					}

					$er = new phpExifReader($srcImage);
					$er->processFile();
					$imageInfo = $er->getImageInfo();

			        $exifArray = array();
			        
			        if (isset($imageInfo['make'])) $exifArray["Camera"] = $imageInfo['make'];
			        if (isset($imageInfo['model'])) $exifArray["Model"] = $imageInfo['model'];
			        if (isset($imageInfo['focalLength'])) $exifArray["Focal distance"] = $imageInfo['focalLength'];
			        
			        if (isset($imageInfo['fnumber'])) {
			        	 $exifArray["Diafragma"] = $imageInfo['fnumber'];
			        } else {
				        if (isset($imageInfo['aperture'])) $exifArray["Aperture"] = $imageInfo['aperture'];
			        }
			        if (isset($imageInfo['exposureTime'])) $exifArray["Shutter speed"] = $imageInfo['exposureTime'];
			        if (isset($imageInfo['isoEquiv'])) $exifArray["ISO"] = $imageInfo['isoEquiv'];
			        if (isset($imageInfo['DateTime'])) $exifArray["Date"] = $imageInfo['DateTime'];

					$newImage 			= imagecreatetruecolor($destinationWidth,$destinationHeight);
					
					switch($imageType) {
						case "image/gif":
							$source=imagecreatefromgif($srcImage); 
							break;
					    case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
							$source=imagecreatefromjpeg($srcImage); 
							break;
					    case "image/png":
						case "image/x-png":
							$source=imagecreatefrompng($srcImage); 
							break;
				  	}
				  	
					imagecopyresampled($newImage,$source,0,0,0,0,$destinationWidth,$destinationHeight,$imagewidth,$imageheight);
					
					switch($imageType) {
						case "image/gif":
					  		imagegif($newImage,$destImage); 
							break;
				      	case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
					  		imagejpeg($newImage,$destImage,100); 
							break;
						case "image/png":
						case "image/x-png":
							imagepng($newImage,$destImage);  
							break;
				    }
					
					imagedestroy($newImage);
					imagedestroy($source);
					chmod($destImage, 0777);
					
					$returnValues = array();
					$returnValues["image"] = $destImage;
					$returnValues["width"] = $destinationWidth;
					$returnValues["height"] = $destinationHeight;
					$returnValues["exif"] = urlencode(serialize($exifArray));
					return $returnValues;					
				
				} else {
					@unlink($destImage);
					return false;
				}				
		
				
			} catch (Exception $e) {
				return false;
			}
		
		
		} 
	
		public static function bringToSize($image,$imWidth,$imHeight) {

			$maxWidth = $imWidth;
			$maxHeight = $imHeight;
			
			ini_set('gd.jpeg_ignore_warning', 1);
			error_reporting (E_ALL ^ E_NOTICE);
			
			try {
			
				$result = getimagesize($image);

				if ($result) {
								
					list($imagewidth, $imageheight, $imageType) = $result;
					$imageType = image_type_to_mime_type($imageType);

					$newImage = imagecreatetruecolor($maxWidth,$maxHeight);
					switch($imageType) {
						case "image/gif":
							$source=imagecreatefromgif($image); 
							break;
					    case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
							$source=imagecreatefromjpeg($image); 
							break;
					    case "image/png":
						case "image/x-png":
							$source=imagecreatefrompng($image); 
							break;
				  	}
				  	
					imagecopyresampled($newImage,$source,0,0,0,0,$maxWidth,$maxHeight,$imagewidth,$imageheight);
					
					switch($imageType) {
						case "image/gif":
					  		imagegif($newImage,$image); 
							break;
				      	case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
					  		imagejpeg($newImage,$image,100); 
							break;
						case "image/png":
						case "image/x-png":
							imagepng($newImage,$image);  
							break;
				    }
					
					imagedestroy($newImage);
					chmod($image, 0777);
					$returnValues = array();
					$returnValues["image"] = $image;
					$returnValues["width"] = $newWidth;
					$returnValues["height"] = $newHeight;
					
					return $returnValues;					
				
				} else {
					@unlink($image);
					return false;
				}				
		
				
			} catch (Exception $e) {
				return false;
			}

		}	
	
		public static function resizeImage($image, $thumb, $flashThumbFile) {

			$maxWidth = 600;
			$maxHeight = 600;
			
			$maxThumbWidth = 100;
			$maxThumbHeight = 100;

			$flashThumbWidth = 200;
			$flashThumbHeight = 150;
			
			$invertRatioX = $maxThumbWidth / $maxWidth;
			$invertRatioY = $maxThumbHeight / $maxHeight;
			
			$finalAspectRatio = $maxWidth / $maxHeight;
			$flashThumbAspectRatio = $flashThumbWidth / $flashThumbHeight;
			
			$thumbAspectRatio = 1;
			
			ini_set('gd.jpeg_ignore_warning', 1);
			error_reporting (E_ALL ^ E_NOTICE);
			
			try {
			
				$result = getimagesize($image);

				if ($result) {
								
					list($imagewidth, $imageheight, $imageType) = $result;
					$imageType = image_type_to_mime_type($imageType);
					
					$aspectRatio = $imagewidth / $imageheight;
					
					if ($aspectRatio > $finalAspectRatio) {
						$newWidth = $maxWidth;
						$newHeight = ceil($newWidth / $aspectRatio);
					} else {
						$newHeight = $maxHeight;
						$newWidth = ceil($newHeight * $aspectRatio);
					}
					
					if ($aspectRatio > $thumbAspectRatio) {
						$thumbWidth = $maxThumbWidth;
						$thumbHeight = ceil($thumbWidth / $aspectRatio);
					} else {
						$thumbHeight = $maxThumbHeight;
						$thumbWidth = ceil($thumbHeight * $aspectRatio);
					}

					if ($aspectRatio > $flashThumbAspectRatio) {
						$newFlashWidth = $flashThumbWidth;
						$newFlashHeight = ceil($newFlashWidth / $aspectRatio);
					} else {
						$newFlashHeight = $flashThumbHeight;
						$newFlashWidth = ceil($newFlashHeight * $aspectRatio);
					}


					
					/*
			        $exif_data = @exif_read_data($image);
			        $exifArray = array();
			        $exifArray["Camera"] = $exif_data['Make'];
			        $exifArray["Model"] = $exif_data['Model'];
			        $exifArray["Exposure Time"] = $exif_data['ExposureTime'];
			        $exifArray["F Number"] = $exif_data['FNumber'];
			        $exifArray["ISO Speed Ratings"] = $exif_data['ISOSpeedRatings'];
			        $exifArray["DateTime"] = $exif_data['DateTimeOriginal'];
			        var_dump($exif_data);
			        */
					$er = new phpExifReader($image);
					$er->processFile();
					$imageInfo = $er->getImageInfo();

			        $exifArray = array();
			        
			        if (isset($imageInfo['make'])) $exifArray["Camera"] = $imageInfo['make'];
			        if (isset($imageInfo['model'])) $exifArray["Model"] = $imageInfo['model'];
			        if (isset($imageInfo['focalLength'])) $exifArray["Distanta focala"] = $imageInfo['focalLength'];
			        
			        if (isset($imageInfo['fnumber'])) {
			        	 $exifArray["Diafragma"] = $imageInfo['fnumber'];
			        } else {
				        if (isset($imageInfo['aperture'])) $exifArray["Diafragma"] = $imageInfo['aperture'];
			        }
			        if (isset($imageInfo['exposureTime'])) $exifArray["Timp de expunere"] = $imageInfo['exposureTime'];
			        if (isset($imageInfo['isoEquiv'])) $exifArray["ISO"] = $imageInfo['isoEquiv'];
			        if (isset($imageInfo['DateTime'])) $exifArray["Data"] = $imageInfo['DateTime'];

					/*
			        $emake =$exif_data['Make'];
			        $emodel = $exif_data['Model'];
			        $eexposuretime = $exif_data['ExposureTime'];
			        $efnumber = $exif_data['FNumber'];
			        $eiso = $exif_data['ISOSpeedRatings'];
			        $edate = $exif_data['DateTime'];
			        */
			        
										
					$newImage 			= imagecreatetruecolor($newWidth,$newHeight);
					$thumbImage 		= imagecreatetruecolor($thumbWidth,$thumbHeight);
					$flashThumbImage 	= imagecreatetruecolor($flashThumbWidth,$flashThumbHeight);
					
					$grey 				= imagecolorallocate($flashThumbImage, 50, 50, 50);
					imagefill($flashThumbImage, 0, 0, $grey);

					switch($imageType) {
						case "image/gif":
							$source=imagecreatefromgif($image); 
							break;
					    case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
							$source=imagecreatefromjpeg($image); 
							break;
					    case "image/png":
						case "image/x-png":
							$source=imagecreatefrompng($image); 
							break;
				  	}
				  	
					imagecopyresampled($newImage,$source,0,0,0,0,$newWidth,$newHeight,$imagewidth,$imageheight);
					imagecopyresampled($thumbImage,$source,0,0,0,0,$thumbWidth,$thumbHeight,$imagewidth,$imageheight);
					
					$dst_x = ceil(($flashThumbWidth-$newFlashWidth)/2);
					$dst_y = ceil(($flashThumbHeight-$newFlashHeight)/2);
					
					imagecopyresampled($flashThumbImage,$source,$dst_x,$dst_y,0,0,$newFlashWidth,$newFlashHeight,$imagewidth,$imageheight);
					
					switch($imageType) {
						case "image/gif":
					  		imagegif($newImage,$image); 
					  		imagegif($thumbImage,$thumb); 
					  		imagegif($flashThumbImage,$flashThumbFile); 
							break;
				      	case "image/pjpeg":
						case "image/jpeg":
						case "image/jpg":
					  		imagejpeg($newImage,$image,100); 
					  		imagejpeg($thumbImage,$thumb,100); 
					  		imagejpeg($flashThumbImage,$flashThumbFile,100); 
							break;
						case "image/png":
						case "image/x-png":
							imagepng($newImage,$image);  
							imagepng($thumbImage,$thumb);  
					  		imagepng($flashThumbImage,$flashThumbFile); 
							break;
				    }
					
					imagedestroy($newImage);
					imagedestroy($thumbImage);
					imagedestroy($flashThumbImage);
					chmod($image, 0777);
					chmod($thumb, 0777);
					chmod($flashThumbFile, 0777);
					$returnValues = array();
					$returnValues["image"] = $image;
					$returnValues["width"] = $newWidth;
					$returnValues["height"] = $newHeight;
					$returnValues["twidth"]  = ceil($newWidth * $invertRatioX);
					$returnValues["theight"] = ceil($newHeight * $invertRatioY);
					$returnValues["exif"] = urlencode(serialize($exifArray));
					
					return $returnValues;					
				
				} else {
					@unlink($image);
					return false;
				}				
		
				
			} catch (Exception $e) {
				return false;
			}

		}
		
		
		//You do not need to alter these functions
		public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $startX, $startY, $scale){
			ini_set("memory_limit","64M");
			ini_set('gd.jpeg_ignore_warning', 1);
			list($imagewidth, $imageheight, $imageType) = getimagesize($image);
			$imageType = image_type_to_mime_type($imageType);
			
			$newImageWidth = ceil($width * $scale);
			$newImageHeight = ceil($height * $scale);
			$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($image); 
					break;
			    case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($image); 
					break;
			    case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($image); 
					break;
		  	}
		  	
			imagecopyresampled($newImage,$source,0,0,$startX,$startY,$newImageWidth,$newImageHeight,$width,$height);
			switch($imageType) {
				case "image/gif":
			  		imagegif($newImage,$thumb_image_name); 
					break;
		      	case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
			  		imagejpeg($newImage,$thumb_image_name,100); 
					break;
				case "image/png":
				case "image/x-png":
					imagepng($newImage,$thumb_image_name);  
					break;
		    }
			chmod($thumb_image_name, 0777);
			return $thumb_image_name;
		}
		
		
		//You do not need to alter these functions
		public static function makeThumbnail($thumb_image_name, $image, $width, $height, $startX, $startY){
			ini_set("memory_limit","64M");
			ini_set('gd.jpeg_ignore_warning', 1);
			list($imagewidth, $imageheight, $imageType) = getimagesize($image);
			$imageType = image_type_to_mime_type($imageType);

			$aspectRatio = $width / $height;
			$finalAspectRatio = 100/75;
			
			if ($aspectRatio > $finalAspectRatio) {
				$newWidth = 100;
				$newHeight = ceil($height * 100 / $width);
			} else {
				$newWidth = ceil(75 * $width / $height);
				$newHeight = 75;
			}

			$newImage = imagecreatetruecolor($newWidth,$newHeight);
			switch($imageType) {
				case "image/gif":
					$source=imagecreatefromgif($image); 
					break;
			    case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($image); 
					break;
			    case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($image); 
					break;
		  	}
		  	
			imagecopyresampled($newImage,$source,0,0,$startX,$startY,$newWidth,$newHeight,$width,$height);
			switch($imageType) {
				case "image/gif":
			  		imagegif($newImage,$thumb_image_name); 
					break;
		      	case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
			  		imagejpeg($newImage,$thumb_image_name,100); 
					break;
				case "image/png":
				case "image/x-png":
					imagepng($newImage,$thumb_image_name);  
					break;
		    }
			chmod($thumb_image_name, 0777);
			return $thumb_image_name;
		}		
		
		//You do not need to alter these functions
		public function getHeight($image) {
			$size = getimagesize($image);
			$height = $size[1];
			return $height;
		}
		
		//You do not need to alter these functions
		public function getWidth($image) {
			$size = getimagesize($image);
			$width = $size[0];
			return $width;
		}
		
	}

?>