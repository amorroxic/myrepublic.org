<?php

class ViziteManager
{

	public $viziteTable;

	public function ViziteManager() {
		$this->viziteTable = new Vizite();
	}

	public function getVizite() 
	{
		$select = $this->viziteTable->select();		
		$select->order(array('id'));					
		$rows = $this->viziteTable->fetchAll($select);
		return $rows->toArray();
	}
	
}