<?php

class Zend_View_Helper_ShowDate extends Zend_View_Helper_Abstract
{

	public $view;

	public function showDate($theDate) {

		$dateStart = new Zend_Date(strtotime($theDate),Zend_Date::TIMESTAMP);
		$sessionMonthName = $this->view->t($dateStart->get(Zend_Date::MONTH_NAME));
		$sessionYear = $dateStart->get(Zend_Date::YEAR);
		$sessionDay = $dateStart->get(Zend_Date::DAY);
		$date = $sessionDay." ".$sessionMonthName." ".$sessionYear;		
		return $date;
	}


}
