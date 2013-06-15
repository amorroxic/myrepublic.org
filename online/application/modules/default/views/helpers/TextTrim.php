<?php

class Zend_View_Helper_TextTrim extends Zend_View_Helper_Abstract
{

	public function textTrim($text="",$length=10) {

		if (strlen($text) > $length) $text = substr($text, 0, $length-2)."..";
		return $text;
		
	}


}
