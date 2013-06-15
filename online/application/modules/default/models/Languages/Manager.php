<?php

class Languages_Manager
{
	
	public $table;
	public $rows;
	

	public function __construct()
	{
		$this->table = new Languages_Table();
	}
		
	public function getLanguageList($id = null) 
	{
		$select = $this->table->select();
		if (isset($id)) $select->where('id = ?', $id);
		$records = $this->table->fetchAll($select);
		$records = $records->toArray();
		return $records;
	}

}