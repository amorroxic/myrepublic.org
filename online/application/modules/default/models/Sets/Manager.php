<?php

class Sets_Manager
{
	
	public $setsTable;
	public $rows;
	
	public function __construct ()
	{
		$this->setsTable = new Sets_Table();
	}
	
	public function getSets($id = null) {
	
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->from(array('s' => 'user_sets'))
		             ->joinInner(array('u' => 'site_users'),'u.id = s.user_id',array('u.first_name','u.last_name','u.email'));

		if (isset($id) && is_numeric($id)) $select->where('s.id = ?', $id);
		$select->order(array('s.published desc'));
		$records = $db->fetchAll($select);

		return $records;
		
	}
	
	public function getPhotoIDSFromSet($setID) {
		if (!isset($setID)) return array();
		if (!is_numeric($setID)) return array();

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->from(array('s' => 'set_photos'));

		$select->where('s.set_id = ?', $setID);
		$records = $db->fetchAll($select);
		
		$setPhotos = array();
		foreach ($records as $photo) {
			$setPhotos[] = $photo["photo_id"];
		}

		return $setPhotos;
		
	}
	
	public function getSetsByUser($uid = null) {
	
		if (!isset($uid)) return array();
		if (!is_numeric($uid)) return array();
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->from(array('s' => 'user_sets'))
		             ->joinInner(array('u' => 'site_users'),'u.id = s.user_id',array('u.first_name','u.last_name','u.email'));
		$select->where('u.id = ?', $uid);
		$select->order(array('s.published desc'));
		$records = $db->fetchAll($select);

		return $records;
		
	}
	
	public function add($setName = "",$uid = null) {
		if ($setName == "") return;
		if (!isset($uid)) return;
		if (!is_numeric($uid)) return;
		
		$fields = array(
			"name" 		=> $setName,
			"user_id" 	=> $uid,
			"published"	=> date("Y-m-d g:i:s")
		);
		$id = $this->setsTable->insert($fields);
		return $id;
	}	
		
	public function deleteByID($id = null) {
	
		if (!isset($id)) return false;
		if (!is_numeric($id)) return false;
		
		if (Authentication_Manager::isAllowed()) {
		
			$userData = Authentication_Manager::me();
	        $where = 'id = ' . $id.' and user_id = '.$userData->id;
			$this->setsTable->delete($where);

			$setPhotos = new Sets_Photos();
	        $where = 'set_id = ' . $id;
			$setPhotos->delete($where);
			
		}
	
		return true;
	
	}


	public function deleteByUser() {
	
		
		if (Authentication_Manager::isAllowed()) {
		
			$userData = Authentication_Manager::me();
	        $where = 'user_id = '.$userData->id;
			$this->setsTable->delete($where);
			
		}
	
		return true;
	
	}
	
	public function assignPhotoToSet($photoID, $setID) {

		$setPhotos = new Sets_Photos();

		if (Authentication_Manager::isAllowed()) {
		
			$fields = array(
				"photo_id" 	=> $photoID,
				"set_id"	=> $setID
			);
		
			$setPhotos->insert($fields);
			
		}
		
	}

	public function removePhotoFromSet($photoID, $setID) {

		$setPhotos = new Sets_Photos();

		if (Authentication_Manager::isAllowed()) {
			$where = "set_id = ".$setID." and photo_id=".$photoID;		
			$setPhotos->delete($where);
		}
		
	}	
	
		
}