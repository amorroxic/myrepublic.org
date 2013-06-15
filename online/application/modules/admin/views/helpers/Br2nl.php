<?php

class Zend_View_Helper_Br2nl
{
	function Br2nl($text)
	{
      /* Remove XHTML linebreak tags. */
      $text = str_replace("<br />","",$text);
      /* Remove HTML 4.01 linebreak tags. */
      $text = str_replace("<br>","",$text);
      /* Return the result. */
      $text = str_replace("<br/>","",$text);
      /* Return the result. */
      return $text;
	}
}