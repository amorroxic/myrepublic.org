<?php

class UserShopData
{
	private $_changed;
	private $shopData;
	
	public function __construct() {
		$this->reset();
	}
	
	public function add($productID, $quantity = 1) {
		$quantity = intval($quantity);
		if (!is_numeric($productID) || !is_numeric($quantity)) return false;
		$this->_changed = true;
		if (!isset($this->shopData)) $this->shopData = array();
		$shopItem = $this->find($productID);
		if (!isset($shopItem)) {
			$shopItem = array();
			$shopItem["id"] = $productID;
			$shopItem["quantity"] = $quantity;
		} else {
			$shopItem["quantity"] += $quantity;
			$this->remove($productID);
		} 
		$this->shopData[] = $shopItem;
	}
	
	public function setQuantity($productID, $quantity = null) {
		$quantity = intval($quantity);		
		if (!is_numeric($productID) || !is_numeric($quantity)) return false;
		$this->_changed = true;
		if (!isset($this->shopData)) $this->shopData = array();
		$shopItem = $this->find($productID);
		if (!isset($shopItem)) {
			$shopItem = array();
			$shopItem["id"] = $productID;
			$shopItem["quantity"] = $quantity;
		} else {
			$shopItem["quantity"] = $quantity;
			$this->remove($productID);
		} 
		if ($shopItem["quantity"]>0) $this->shopData[] = $shopItem;
	}
		
	
	public function find($productID) {
		foreach ($this->shopData as $shopItem) {
			if ($shopItem["id"] == $productID) return $shopItem;
		}
		return null;
	}
		
	public function remove($productID, $quantity = null) {

		$this->_changed = true;		

		foreach ($this->shopData as $i => $value) {
			if ($value["id"]==$productID) {
				if (isset($quantity)) {
					$this->shopData[$i]["quantity"] -= $quantity;
					if ($value["quantity"]<=0)  unset($this->shopData[$i]);
				} else {
					unset($this->shopData[$i]);
				}
				
			}
		}

	}
	
	public function emptybasket() {
		$this->_changed = true;		
		unset($this->shopData);
		$this->shopData = array();
	}
	
	public function get($productID = null) {
		if (isset($productID)) return $this->find($productID);
		return $this->shopData;
	}
	
	public function changed() {
		return $this->_changed;
	}
	
	public function reset() {
		$this->_changed = false;
	}

	public function __wakeup() {
		$this->reset();
	}
}
