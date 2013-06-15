<?php

class Photos_Comments_Manager
{
	
	public $commentsTable;
	public $rows;
	public $filtru;
	public $search;
	public $userSearch;
	
	public function __construct ()
	{
		$this->commentsTable = new Photos_Comments_Table();
		$this->filtru = "aprobate";
	}

	public function setFilter($filtru = "aprobate") {
		$this->filtru = $filtru;
	}

	public function setSearchString($string) {
		$this->search = $string;
	}
	public function setUserSearchString($string) {
		$this->userSearch = $string;
	}

	
	public function getComments($photoID) {

		$select = $this->commentsTable->select();
		switch ($this->filtru) {
			case "aprobate":
							$select->where('approved = 1');
							break;
			case "neaprobate":
							$select->where('approved = 0');
							break;
			case "toate":
							break;
		}
		$select->where('photo_id = ?', $photoID);
		//$select->order(array('id DESC'));
		$records = $this->commentsTable->fetchAll($select);
		$records = $records->toArray();
		return $records;
		
	}
	
	public function getAllComments() {

		$select = $this->commentsTable->select();
		switch ($this->filtru) {
			case "aprobate":
							$select->where('approved = 1');
							break;
			case "neaprobate":
							$select->where('approved = 0');
							break;
			case "toate":
							break;
		}
		$records = $this->commentsTable->fetchAll($select);
		$records = $records->toArray();
		return $records;
		
	}
	
	public function getAllCommentsWithPhotos($user_id = null,$limit = null) {
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('c' => 'user_photo_comments'))
		             ->joinInner(array('p' => 'user_photos'),'c.photo_id = p.id',array('photo_category','thumb','description','p_user_id' => 'user_id'));
		if (isset($this->userSearch)) {
         	$select->joinInner(array('u' => 'site_users'),'c.user_id = u.id',array());
			$select->where('u.first_name like ? or u.last_name like ?', '%'.$this->userSearch.'%','%'.$this->userSearch.'%');
		}
		if (isset($this->search)) $select->where('c.comment like ?', '%'.$this->search.'%');
		             
		if (isset($user_id)) $select->where('p.user_id = ?',$user_id);

		switch ($this->filtru) {
			case "aprobate":
							$select->where('c.approved = 1');
							break;
			case "neaprobate":
							$select->where('c.approved = 0');
							break;
			case "toate":
							break;
		}
		if (isset($limit)) { 
			$select->order(array('c.id DESC'));
			$select->limit($limit,0);
		}

		$result = $db->fetchAll($select);
		return $result;
	
	
	}	

}