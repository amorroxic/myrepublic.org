$jpeg = new PelJpeg($image);
$exif = $jpeg->getExif();
$tiff = $exif->getTiff();
$ifd = $tiff->getIfd();

$entries = $ifd->getEntries();

  foreach ($entries as $tag => $entry) {
            echo PelTag::getName($ifd->getType(), $tag)." = ".$entry->getValue()."<br/>";
            //echo $tag . "<br/>";
            //$desc = $ifd->getEntry(PelTag::MAKE)
    //entryToTest('$entry', $entry);
  }
  
$sub_ifds = $ifd->getSubIfds();

  foreach ($sub_ifds as $type => $sub_ifd) {
	echo $sub_ifd->getName()."<br/>";
    $entries = $sub_ifd->getEntries();
	  foreach ($entries as $tag => $entry) {
	            echo PelTag::getName($sub_ifd->getType(), $tag)." = ".$entry->getValue()."<br/>";
	  }


  
  }

  				        
die();
$desc = $ifd->getEntry(PelTag::MAKE);
if (isset($desc)) echo $desc->getValue()."<br/>";
$desc = $ifd->getEntry(PelTag::MODEL);
if (isset($desc)) echo $desc->getValue()."<br/>";
$desc = $ifd->getEntry(PelTag::FOCAL_LENGTH);
if (isset($desc)) echo $desc->getValue()."<br/>";
$desc = $ifd->getEntry(PelTag::APERTURE_VALUE);
if (isset($desc)) echo $desc->getValue()."<br/>";
$desc = $ifd->getEntry(PelTag::SHUTTER_SPEED_VALUE);
if (isset($desc)) echo $desc->getValue()."<br/>";
$desc = $ifd->getEntry(PelTag::DATE_TIME_ORIGINAL);
if (isset($desc)) echo $desc->getValue()."<br/>";


/*
$entries = $ifd->getEntries();
  foreach ($entries as $tag => $entry) {
            echo PelTag::getName($ifd->getType(), $tag)."<br/>";
    //entryToTest('$entry', $entry);
  }			        
*/

//var_dump($entries);