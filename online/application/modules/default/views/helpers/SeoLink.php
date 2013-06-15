<?php

class Zend_View_Helper_SeoLink extends Zend_View_Helper_Abstract
{

	public function seoLink($contentText="",$contentID=0) {
		
		$contentLink = "";
		
		if ($contentText != "") {

			if (strlen($contentText) > 100) $contentText = substr($contentText, 0, 100);
			if ($contentID != 0) {
				$contentLink = $contentID."/".ereg_replace("[^A-Za-z0-9]", "-", $contentText).".htm";
			} else {
				$contentLink = ereg_replace("[^A-Za-z0-9]", "-", $contentText);
			}
			$contentLink = preg_replace('{\-+}', '-', $contentLink);

		}
	
		return $contentLink;							

	}

}
