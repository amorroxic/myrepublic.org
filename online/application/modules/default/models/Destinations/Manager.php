<?php

class Destinations_Manager
{
	
	public $destinationsTable;
	private $level;
	
	private $keysDestinationIDToCountryID;
	private $_countries;
	private $_destinations;
	
	private $usePaging;
	private $page;
	private $recordsPerPage;
	private $pagesInRange;
	
	public function __construct ()
	{
		$this->destinationsTable = new Destinations_Table();
		$this->keysDestinationIDToCountryID = array();
		$this->_countries = array();
		$this->_destinations = array();
		
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

	public function getPlaces($parentCountry = 0, $search = null) {
		$select = $this->destinationsTable->select();
		if (is_numeric($parentCountry)) $select->where('parent_id = ?', $parentCountry);
		if (isset($search)) $select->where('name like ?', '%'.$search.'%');
		$select->order('name asc');
		$rows = $this->destinationsTable->fetchAll($select)->toArray();
		return $rows;
	}
	
	
		
	public function search($search=null) {
		
		if (!isset($search)) return null;
		
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('c' => 'destinations'))
		             ->joinInner(array('d' => 'destinations'),'c.id = d.parent_id',array("destination_name" => "d.name", "destination_id"=>"d.id"));
		$select->where('c.name like ?','%'.$search.'%');
		$select->orWhere('d.name like ?','%'.$search.'%');
		$select->order(array('c.id asc','d.id asc'));
		$rows = $db->fetchAll($select);
		
		$countries = $this->groupByCountry($rows);
		
		if (count($countries) == 0) return null;
		
		return $countries;

		
	}
	
	public function searchWithPhotos($search=null) {
		
		if (!isset($search)) return null;
		
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('p' => 'user_photos'))
		             ->joinInner(array('d' => 'destinations'),'p.destination_id = d.id',array("destination_name" => "d.name", "destination_id"=>"d.id"))
		             ->joinInner(array('c' => 'destinations'),'d.parent_id = c.id',array("country_name" => "c.name", "country_id"=>"c.id"));
		$select->where('c.name like ?','%'.$search.'%');
		$select->orWhere('d.name like ?','%'.$search.'%');
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
	
	public function getCountry($countryID, $destinationList = null) {

		if (!isset($destinationList)) $destinationList = $this->getDestinations();
				
		foreach ($destinationList as $destination) {
			if ($destination["id"] == $countryID) {
				return $destination;
			}
		}
		
		return null;
	}

	public function getCountriesWithPlaces($countryID=null) {

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('c' => 'destinations'))
		             ->joinInner(array('d' => 'destinations'),'c.id = d.parent_id',array("destination_name" => "d.name", "destination_id"=>"d.id"));
		if (isset($countryID)) $select->where('c.id = ?',$countryID);
		$select->where('c.parent_id = 0');
		$select->order(array('c.id asc','d.id asc'));
		$rows = $db->fetchAll($select);
		
		$countries = $this->groupByCountry($rows);
		
		if (count($countries) == 0) return null;
		
		if (isset($countryID)) $countries = $countries[0];
		
		return $countries;
		
	}
	
	public function getCountriesWithOrWithoutPlaces($countryID=null) {
	
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('c' => 'destinations'))
		             ->joinLeft(array('d' => 'destinations'),'c.id = d.parent_id',array("destination_name" => "d.name", "destination_id"=>"d.id"));
		if (isset($countryID)) $select->where('c.id = ?',$countryID);
		$select->where('c.parent_id = 0');
		$select->order(array('c.id asc','d.id asc'));
		$rows = $db->fetchAll($select);
		
		$countries = $this->groupByCountry($rows);
		
		if (count($countries) == 0) return null;
		if (isset($countryID)) $countries = $countries[0];
		
		return $countries;

	}
	
	public function getCountriesWithoutPlaces($countryID=null) {

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('c' => 'destinations'));
		if (isset($countryID)) $select->where('c.id = ?',$countryID);
		$select->where('c.parent_id = 0');
		$select->order(array('c.id asc'));
		$countries = $db->fetchAll($select);
		
		if (count($countries) == 0) return null;
		if (isset($countryID)) $countries = $countries[0];
		
		return $countries;
		
	}
		
	public function getCountryByDestinationID($destinationID = null) {

		$destination = $this->getDestination($destinationID);
		if (isset($destination)) return $this->getCountriesWithPlaces($destination["parent_id"]);

	}
		

	public function getDestination($destinationID = null) {

		if (!isset($destinationID)) return null;
		if (!is_numeric($destinationID)) return null;

		$select = $this->destinationsTable->select();
		$select->where('id = ?', $destinationID);
		$rows = $this->destinationsTable->fetchRow($select);
		if (count($rows) == 0) return null;
		$destination = $rows->toArray();
		return $destination;
	}	

		
	private function groupByCountry($rows) {
	
		$outputCountries = array();
		$currentCountryID = null;
		$currentDestinationID = null;
		
		foreach ($rows as $row) {

			if ($currentCountryID != $row["id"]) {
				$currentDestinationID = null;
				unset($newCountry);
				$newCountry = array();
				$newCountry["id"] = $row["id"];
				$newCountry["name"] = $row["name"];
				$newCountry["parent_id"] = "0";
				$newCountry["places"] = array();
				$outputCountries[] = $newCountry;
			}
			
			if ($currentDestinationID != $row["destination_id"]) {
				unset($newDestination);
				$newDestination = array();
				$newDestination["id"] = $row["destination_id"];
				$newDestination["name"] = $row["destination_name"];
				$newDestination["parent_id"] = $row["id"];
				$lastCountry = count($outputCountries)-1;
				$outputCountries[$lastCountry]["places"][] = $newDestination;
			}

			$currentCountryID = $row["id"];
			$currentDestinationID = $row["destination_id"];
			
		}
				
		return $outputCountries;
	}	
	
	public function getCountryWithoutInfo($countryID) {
		$select = $this->destinationsTable->select();
		$select->where('parent_id = 0');
		$select->where('id = ?', $countryID);
		$rows = $this->destinationsTable->fetchAll($select)->toArray();
		return $rows;
	}		

	public function addDestination($destinationName,$parent = 0) {

			if (!isset($destinationName)) return -1;
			if ($parent < 0) return -1;

			$select = $this->destinationsTable->select();
			$select->where('name = ?', $destinationName);
			$select->where('parent_id = ?', $parent);
			$rows = $this->destinationsTable->fetchAll($select);
			if (count($rows)>0) return $rows[0]->id;
			
			$statement = array(
				'name'				=>$destinationName,
				'parent_id'			=>$parent
			);

			$this->destinationsTable->insert($statement);
			return Zend_Registry::get('dbAdapter')->lastInsertId();
		
	}
	
	public function getUserDestinations($userID) {
		
		$countries = $this->getPlaces();
			
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('p' => 'user_photos'),'d.id = p.destination_id',array());
		             
		$select->where('p.user_id = ?',$userID);
		$select->order(array('d.parent_id asc'));
		$destinations = $db->fetchAll($select);
		
		$userCountries = array();
		foreach ($countries as $country) {
			
			$countryDestinations = array();
			
			foreach ($destinations as $destination) {
				if ($destination["parent_id"] == $country["id"]) {
					$countryDestinations[] = $destination;
				}
			}
			
			if (count($countryDestinations)>0) {
				$country["places"] = $countryDestinations;
				$userCountries[] = $country;
			}

		}
		
		return $userCountries;
			
		
	}

	public function getUserFavorites($userID) {
		
		$countries = $this->getPlaces();
			
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('p' => 'user_photos'),'d.id = p.destination_id',array())
		             ->joinInner(array('f' => 'favorites'),'p.id = f.photo_id',array());
		             
		$select->where('f.user_id = ?',$userID);
		$select->order(array('d.parent_id asc'));
		$destinations = $db->fetchAll($select);
		
		$userCountries = array();
		foreach ($countries as $country) {
			
			$countryDestinations = array();
			
			foreach ($destinations as $destination) {
				if ($destination["parent_id"] == $country["id"]) {
					$countryDestinations[] = $destination;
				}
			}
			
			if (count($countryDestinations)>0) {
				$country["places"] = $countryDestinations;
				$userCountries[] = $country;
			}

		}
		
		return $userCountries;
			
		
	}
	
	public function getNewestDestinations() {

		$countries = $this->getPlaces();
			
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
					 ->distinct()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('p' => 'user_photos'),'d.id = p.destination_id',array());
		             
		$select->order(array('p.published desc'));
		$destinations = $db->fetchAll($select);
		
		$outCountries = array();
		
		foreach ($destinations as $destination) {
		
			foreach ($countries as $country) {
				
				if ($destination["parent_id"] == $country["id"]) {
					$outCountry = $country;
					$outCountry["places"] = array();
					$outCountry["places"][] = $destination;
					$outCountries[] = $outCountry;
				}
			
			}
		
		}
		
		return $outCountries;
		
	}

	public function getCoolestDestinations() {

		$countries = $this->getPlaces();

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('p' => 'user_photos'),'d.id = p.destination_id',array())
		             ->joinInner(array('f' => 'favorites'),'f.photo_id = p.id',
		                    array('faves' => 'COUNT(*)'))
		             ->group('d.id');
		             		             
		$select->order(array('faves DESC','d.id DESC'));
		$destinations = $db->fetchAll($select);
		
		$outCountries = array();
		
		foreach ($destinations as $destination) {
		
			foreach ($countries as $country) {
				
				if ($destination["parent_id"] == $country["id"]) {
					$outCountry = $country;
					$outCountry["places"] = array();
					$outCountry["places"][] = $destination;
					$outCountries[] = $outCountry;
				}
			
			}
		
		}
		
		return $outCountries;
		
	}
	
	public function getDestinationsWithCountries() {

		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_id"=>"dc.id","country_name"=>"dc.name"));
		$select->where("d.id != 0");		             		             
		$select->order(array('dc.id','d.id'));
		$destinations = $db->fetchAll($select);
		return $destinations;

	}
	
	public function getDestinationInfo($locationID) {
		$db = Zend_Registry::get('dbAdapter');
		$select = $db->select()
		             ->from(array('d' => 'destinations'))
		             ->joinInner(array('dc' => 'destinations'),'d.parent_id = dc.id',array("country_id"=>"dc.id","country_name"=>"dc.name"));
		$select->where("d.id = ?",$locationID);		             		             
		$select->order(array('dc.id','d.id'));
		$destinations = $db->fetchRow($select);
		return $destinations;
	}
	
	public function delete($destinationID) {
	
		if (!isset($destinationID)) return false;
		if (!is_numeric($destinationID)) return false;
	
		
			
	}


	// old functions
	public function oldgetAllDestinations() {
	
			$select = $this->destinationsTable->select();
			$select->where('parent_id = ?', 0);
			$rows = $this->destinationsTable->fetchAll($select)->toArray();

			$allLocations = array();
			foreach ($rows as $row) {
				$locations = $this->getDestination($row["name"],0);
				$allLocations = array_merge($locations,$allLocations);
			}
			return $allLocations;
	}
	
	public function oldaddDestination($destinationName,$parent = 0) {

			if (!isset($destinationName)) return -1;
			if ($parent < 0) return -1;

			$select = $this->destinationsTable->select();
			$select->where('name = ?', $destinationName);
			$select->where('parent_id = ?', $parent);
			$rows = $this->destinationsTable->fetchAll($select);
			if (count($rows)>0) return $rows[0]->id;
			
			$statement = array(
				'name'				=>$destinationName,
				'parent_id'			=>$parent
			);

			$this->destinationsTable->insert($statement);
			return Zend_Registry::get('dbAdapter')->lastInsertId();
		
	}

	public function oldgetDestination($destinationName, $parentID=null, $includeChilds = true, $addParentHierarchy = true) {

			if (!isset($destinationName)) return -1;
			
			$returnValues = array();
			
			$select = $this->destinationsTable->select();
			$select->where('name = ?', $destinationName);
			if (isset($parentID)) $select->where('parent_id = ?', $parentID);
			$rows = $this->destinationsTable->fetchAll($select)->toArray();
			
			foreach ($rows as $row) {
				$destination = array();
				$destination["id"] = $row["id"];
				$destination["name"] = $row["name"];
				$hierarchy = array();
				if ($addParentHierarchy) $this->findParentHierarchy($row["parent_id"],&$hierarchy);
				$destination["hierarchy"] = $hierarchy;
				$returnValues[] = $destination;
				if ($includeChilds) {
					$this->fetchDestinations($row,&$returnValues,&$hierarchy);
				}
			}
			
			return $returnValues;
			
			
	}
	
	private function oldfindParentHierarchy($id,&$hierarchy) {

			if ($id == 0) return;
			$select = $this->destinationsTable->select();
			$select->where('id = ?', $id);
			$rows = $this->destinationsTable->fetchAll($select)->toArray();
			if (count($rows)>0) {
				$destination = array();
				$destination["id"] = $rows[0]["id"];
				$destination["name"] = $rows[0]["name"];
				$this->findParentHierarchy($rows[0]["parent_id"],&$hierarchy);
				$hierarchy[] = $destination;
				return;
			} else {
				return;
			}
	
	}
	
	private function oldfetchDestinations($parent,&$returnValues,&$hierarchy) {
						
			$select = $this->destinationsTable->select();
			$select->where('parent_id = ?', $parent["id"]);
			$rows = $this->destinationsTable->fetchAll($select)->toArray();
			if (count($rows) <= 0) return;

			$hier = array();
			$hier["id"] = $parent["id"];
			$hier["name"] = $parent["name"];
			$hierarchy[] = $hier;

			foreach ($rows as $row) {

				$destination = array();
				$destination["id"] = $row["id"];
				$destination["name"] = $row["name"];
				$destination["hierarchy"] = $hierarchy;
				$returnValues[] = $destination;
				$currentHierarchy = $hierarchy;
				$this->fetchDestinations($row,&$returnValues,&$hierarchy);
				$hierarchy = $currentHierarchy;
			}

	}

}