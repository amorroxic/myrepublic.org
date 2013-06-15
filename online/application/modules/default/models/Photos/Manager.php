<?php

class Photos_Manager
{
	
	public $photoTable;
	public $rows;
	public $filtru;
	private $usePaging;
	
	public function __construct ()
	{
		$this->photoTable = new Photos_Table();		
		$this->filtru = "toate";
		$this->usePaging = false;
		$this->page = "1";
		$this->recordsPerPage = "1";
		$this->pagesInRange = "1";
	}
	
	public function usePaging($page,$records,$pageRange) {
		$this->usePaging = true;
		$this->page = $page;
		$this->recordsPerPage = $records;
		$this->pagesInRange = $pageRange;
	}
	
	public function getNewestPhotosWithLocations($page=1,$limit=9,$range=5) {
		
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name"=>"d.name", "destination_parent" => "d.parent_id"))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_name"=>"dc.name", "country_id" => "dc.id"));

		switch ($this->filtru) {
			case "aprobate":
							$select->where('p.approved = 1');
							break;
			case "neaprobate":
							$select->where('p.approved = 0');
							break;
			case "toate":
							break;
		}

		$select->order(array('p.published DESC','p.id DESC'));
		
		$paginator = Zend_Paginator::factory($select);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage($limit);
		$paginator->setPageRange($range);
		
		//$records = $db->fetchAll($select);		
		//return $records;		
		return $paginator;
	}	
	
	public function getPhotosByLocation($locationID) {
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name"=>"d.name", "destination_parent" => "d.parent_id"))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_name"=>"dc.name", "country_id" => "dc.id"))
		             ->joinLeft(array('f' => 'favorites'),'f.photo_id = p.id',array('favorite' => 'count(f.id)'))
		             ->group('p.id');
		             
		$select->where("p.destination_id = ?",$locationID);
		$select->order(array('p.id DESC'));

		switch ($this->filtru) {
			case "aprobate":
							$select->where('p.approved = 1');
							break;
			case "neaprobate":
							$select->where('p.approved = 0');
							break;
			case "toate":
							break;
		}
		if ($this->usePaging) {
			$records = Zend_Paginator::factory($select);
			$records->setCurrentPageNumber($this->page);
			$records->setItemCountPerPage($this->recordsPerPage);
			$records->setPageRange($this->pagesInRange);
		} else {
			$records = $db->fetchAll($select);		

/*
	    	$favManager = new Photos_Favorites_Manager();
			$allowed = Authentication_Manager::isAllowed();
*/
	
/*
			for ($i=0; $i<count($records); $i++) {
				if ($allowed) {
					$stat = $favManager->isFavorite($records[$i]["id"]);
					if ($stat == "favorite") {
						$records[$i]["favorite"] = 1;			
					} else {
						$records[$i]["favorite"] = 0;			
					}
				} else {
					$records[$i]["favorite"] = 0;
				}
				
			}
*/

		}
		

		return $records;		
	}
	
	public function getPhotosBySet($setID) {
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name"=>"d.name", "destination_parent" => "d.parent_id"))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_name"=>"dc.name", "country_id" => "dc.id"))
		             ->joinInner(array('s' => 'set_photos'),'s.photo_id = p.id',array())
		             ->group('p.id');
		             
		$select->where("s.set_id = ?",$setID);
		$select->order(array('p.id DESC'));

		if ($this->usePaging) {
			$records = Zend_Paginator::factory($select);
			$records->setCurrentPageNumber($this->page);
			$records->setItemCountPerPage($this->recordsPerPage);
			$records->setPageRange($this->pagesInRange);
		} else {
			$records = $db->fetchAll($select);		
		}

		return $records;		
	}
	
	
	
	public function getUserPhotosByLocation($userID, $locationID="") {
		
		if (!isset($locationID)) return array();
		if (!is_numeric($locationID)) {
			if ($locationID != "all" && $locationID != "set") return array();
		}
		
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name"=>"d.name", "destination_parent" => "d.parent_id"))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_name"=>"dc.name", "country_id" => "dc.id"));

		switch ($this->filtru) {
			case "aprobate":
							$select->where('p.approved = 1');
							break;
			case "neaprobate":
							$select->where('p.approved = 0');
							break;
			case "toate":
							break;
		}

		$select->where("p.user_id = ?",$userID);
		if ($locationID != "all" && $locationID != "set") $select->where("p.destination_id = ?",$locationID);
		$select->order(array('p.published DESC','p.id DESC'));
		
		$records = $db->fetchAll($select);		
		return $records;		
	}
	
	public function getPhotosByCountry($countryID) {
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name"=>"d.name", "destination_parent" => "d.parent_id"))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_name"=>"dc.name", "country_id" => "dc.id"));
		$select->where("dc.id = ?",$countryID);
		$select->order(array('p.id DESC'));
		switch ($this->filtru) {
			case "aprobate":
							$select->where('p.approved = 1');
							break;
			case "neaprobate":
							$select->where('p.approved = 0');
							break;
			case "toate":
							break;
		}

		if ($this->usePaging) {
			$records = Zend_Paginator::factory($select);
			$records->setCurrentPageNumber($this->page);
			$records->setItemCountPerPage($this->recordsPerPage);
			$records->setPageRange($this->pagesInRange);
		} else {
			$records = $this->photoTable->fetchAll($select);		
			$records = $records->toArray();
		}
		

		return $records;		
	}
		
	
	public function getPhotosByLocationAndFavoritedBy($locationID, $userID) {

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('f' => 'favorites'),'p.id = f.photo_id',array());
		             
		$select->where('f.user_id = ?',$userID);
		$select->where('p.destination_id = ?',$locationID);
		switch ($this->filtru) {
			case "aprobate":
							$select->where('f.approved = 1');
							break;
			case "neaprobate":
							$select->where('f.approved = 0');
							break;
			case "toate":
							break;
		}		
		$select->order(array('f.id desc'));
		$records = $db->fetchAll($select);
		
    	$favManager = new Photos_Favorites_Manager();
		$allowed = Authentication_Manager::isAllowed();

		for ($i=0; $i<count($records); $i++) {
			if ($allowed) {
				$stat = $favManager->isFavorite($records[$i]["id"]);
				if ($stat == "favorite") {
					$records[$i]["favorite"] = 1;			
				} else {
					$records[$i]["favorite"] = 0;			
				}
			} else {
				$records[$i]["favorite"] = 0;
			}
			
		}
		
		return $records;
	}
	
	public function getRandomPhotoByLocation($locationID) {

		$select = $this->photoTable->select();
		$select->where('destination_id = ?', $locationID);
		$select->order(array('RAND()'));
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
		$select->limit(1,0);
		$records = $this->photoTable->fetchRow($select);
		if (isset($records)) {
			$records = $records->toArray();
		} else {
			$records = array();
		}
		return $records;		
	}	
	
	public function getRandomPhotosForDestinations($places = null) {
	
		if (!isset($places)) return array();
		$result = array();
		
		
		foreach ($places as $place) {
			$place["photo"] = $this->getRandomPhotoByLocation($place["id"]);
			$result[] = $place;
		}	
		return $result;
	}

	public function getUserPhotos($userID, $limit = null) {
		$select = $this->photoTable->select();
		$select->where('user_id = ?', $userID);
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
		$select->order(array('id DESC'));
		if (isset($limit)) $select->limit($limit,0);
		$records = $this->photoTable->fetchAll($select);		
		$records = $records->toArray();
		return $records;		
	}
	
	public function getUserPhotosWithDestinations($userID) {
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('u' => 'site_users'),'p.user_id = u.id',array('u.email','u.first_name','u.last_name','u.website'))
		             ->joinInner(array('d' => 'destinations'),'d.id = p.destination_id',array("destination_name"=>"d.name"))
		             ->joinInner(array('c' => 'destinations'),'c.id = d.parent_id',array("country_name"=>"c.name","country_id"=>"c.id"));
		             
		$select->where('p.user_id = ?',$userID);
		switch ($this->filtru) {
			case "aprobate":
							$select->where('p.approved = 1');
							break;
			case "neaprobate":
							$select->where('p.approved = 0');
							break;
			case "toate":
							break;
		}
		$select->order(array('c.id asc'));

		if ($this->usePaging) {
			$photos = Zend_Paginator::factory($select);
			$photos->setCurrentPageNumber($this->page);
			$photos->setItemCountPerPage($this->recordsPerPage);
			$photos->setPageRange($this->pagesInRange);
		} else {
			$photos = $db->fetchAll($select);
		}
	
		return $photos;		
	}	
	
	public function searchUserPhotosWithDestinations($search) {
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('u' => 'site_users'),'p.user_id = u.id',array('u.email','u.first_name','u.last_name','u.website'))
		             ->joinInner(array('d' => 'destinations'),'d.id = p.destination_id',array("destination_name"=>"d.name"))
		             ->joinInner(array('c' => 'destinations'),'c.id = d.parent_id',array("country_name"=>"c.name","country_id"=>"c.id"));
		             
		$select->where('u.first_name like ?','%'.$search.'%');
		$select->orWhere('u.last_name like ?','%'.$search.'%');
		$select->orWhere('u.email like ?','%'.$search.'%');
		$select->order(array('p.published desc','c.id asc','d.id asc'));

		if ($this->usePaging) {
			$photos = Zend_Paginator::factory($select);
			$photos->setCurrentPageNumber($this->page);
			$photos->setItemCountPerPage($this->recordsPerPage);
			$photos->setPageRange($this->pagesInRange);
		} else {
			$photos = $db->fetchAll($select);
		}
	
		return $photos;		
	}		
	
	public function delete($photoID = null) {
		if (!isset($photoID)) return false;
		if (!is_numeric($photoID)) return false;
		
		$select = $this->photoTable->select();
		$select->where('id = ?', $photoID);
		$record = $this->photoTable->fetchRow($select);
		if (!isset($record)) return false;
		$photo = $record->toArray();
		

		@unlink($_SERVER["DOCUMENT_ROOT"].$photo["thumb"]);
		@unlink($_SERVER["DOCUMENT_ROOT"].$photo["medium"]);
		@unlink($_SERVER["DOCUMENT_ROOT"].$photo["photo"]);

		$where = "id = ".$photo["id"];
    	$this->photoTable->delete($where);
    	
		$f = new Photos_Favorites_Table();
		$c = new Photos_Comments_Table();
		$v = new Photos_Votes_Table();
    	
		$where = "photo_id = ".$photo["id"];
		$f->delete($where);
		$c->delete($where);
		$v->delete($where);

		return true;		
		
	}
	
	public function getPhoto($photoID = null) {
		if (!isset($photoID)) return false;
		if (!is_numeric($photoID)) return false;
		$fields = array(
			"destination_id" => $locationID
		);
		
		$select = $this->photoTable->select();
		$select->where("id = ?",$photoID);
		
    	$photo = $this->photoTable->fetchRow($select);
		if (isset($photo)) $photo=$photo->toArray();
		return $photo;		
		
	}		
	
	public function assign($photoID = null,$locationID = null) {
		if (!isset($photoID)) return false;
		if (!is_numeric($photoID)) return false;
		if (!isset($locationID)) return false;
		if (!is_numeric($locationID)) return false;

		$fields = array(
			"destination_id" => $locationID
		);
		
		$where = "id = ".$photoID;
    	$this->photoTable->update($fields,$where);

		return true;		
		
	}	
	
	public function deletePhotos($photos = null) {

		if (!isset($photos)) return false;
		if (!is_array($photos)) return false;

		$f = new Photos_Favorites_Table();
		$c = new Photos_Comments_Table();
		$v = new Photos_Votes_Table();
		
		foreach ($photos as $photo) {

			$where = "photo_id = ".$photo["id"];
			$f->delete($where);
			$c->delete($where);
			$v->delete($where);

			@unlink($_SERVER["DOCUMENT_ROOT"].$photo["thumb"]);
			@unlink($_SERVER["DOCUMENT_ROOT"].$photo["medium"]);
			@unlink($_SERVER["DOCUMENT_ROOT"].$photo["photo"]);

			$where = "id = ".$photo["id"];
			$this->photoTable->delete($where);
			
		}
		
		return true;
		
	}
	
}