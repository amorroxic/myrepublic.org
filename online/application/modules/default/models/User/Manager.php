<?php

class User_Manager
{
	
	public $userTable;
	public $rows;
	public $usePaging;
	public $recordsPerPage;
	public $currentPage;
	
	public $search;
	public $sortCriteria;

	public function __construct ()
	{
		$this->userTable = new User_Table();
		$this->recordsPerPage = 10;
		$this->currentPage = 1;
	}
	
	public function getPage($page = 0) {
		if ($page > 0) {
			$this->currentPage = $page;
		} else {
			$this->currentPage = 1;
		}
	}	
	
	public function setupRecordsPerPage($recordsPerPage = 0) {
		if ($recordsPerPage > 0) {
			$this->usePaging = true;
			$this->recordsPerPage = $recordsPerPage;
		}
	}
	
	public function setSearchString($string) {
		$this->search = $string;
	}
		
	public function setSortString($string) {
		$this->sortCriteria = $string;
	}
		
	public function fetchFromTable($id = null) {
	
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('u' => 'site_users'))
		             ->joinLeft(array('p' => 'user_photos'),'u.id = p.user_id',array("photos"=>"count(p.id)"))
		             ->group('u.id');

		if (isset($id)) $select->where('u.id = ?', $id);
		if (isset($this->search)) {
			$select->where('u.first_name like ? or u.last_name like ? or u.email like ?', '%'.$this->search.'%','%'.$this->search.'%','%'.$this->search.'%');
		}

		$select->order(array('u.id'));

		if ($this->usePaging) {
			$this->rows = Zend_Paginator::factory($select);
			$this->rows->setCurrentPageNumber($this->currentPage);
			$this->rows->setItemCountPerPage($this->recordsPerPage);
			$this->rows->setPageRange(5);
		} else {
			$this->rows = $this->userTable->fetchAll($select);
		}		
		
	}
	
	public function getUsers($id = null) {
	
		$this->fetchFromTable($id);
		return $this->rows;

	}
		
}