<?php

class Photos_Favorites_Manager
{
	
	public $favoritesTable;
	public $rows;
	

	public function __construct ()
	{
		$this->favoritesTable = new Photos_Favorites_Table();
	}
	
	public function getUserFavorites($userID = null, $photoID = null) {

		$select = $this->favoritesTable->select();
		if (isset($userID)) $select->where('user_id = ?', $userID);
		if (isset($photoID)) $select->where('photo_id = ?', $photoID);
		$records = $this->favoritesTable->fetchAll($select);
		$records = $records->toArray();
		return $records;
		
	}
	
	public function isFavorite($photo_id) {
		$userData = Zend_Auth::getInstance()->getStorage()->read();
		if (isset($userData)) {
			$fav = $this->getUserFavorites($userData->id, $photo_id);
			if (count($fav) > 0) {
				return "favorite";
			} else {
				return "not_favorite";
			}
		} else {
			return "not_auth";
		}
	}

	public function favorite($photo_id) {
		$favoriteStatus = $this->isFavorite($photo_id);
		switch ($favoriteStatus) {
			case "favorite":
							$this->removeFavorite($photo_id);
							return "removed";
							break;
			case "not_favorite":
							$this->addFavorite($photo_id);
							return "added";
							break;
			case "not_auth":
							return "not_auth";
							break;
		}
	}

	public function addFavorite($photo_id) {
		$userData = Zend_Auth::getInstance()->getStorage()->read();
		$statement = array(
			'user_id'	=>$userData->id,
			'photo_id'	=>$photo_id
		);
		
		$this->favoritesTable->insert($statement);
		
	}
	public function removeFavorite($photo_id) {
	
		$userData = Zend_Auth::getInstance()->getStorage()->read();
		$favs = $this->getUserFavorites($userData->id,$photo_id);
		
		foreach ($favs as $fav) {
	        $where = 'id = ' . $fav["id"];
			$this->favoritesTable->delete($where);
		}
		
	
	}

}