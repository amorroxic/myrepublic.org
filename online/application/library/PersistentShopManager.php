<?php

class PersistentShopManager
{

	protected static $_userData;
	
	protected static $_visitorId;
	
	private static $_currentRow;
	
	private static $_dataTable;
	
	private static function _getDataTableInstance() {
		if (is_null(self::$_dataTable)) {
			self::$_dataTable = new PersistentData();	
		}
		return self::$_dataTable;
	}
	
	public static function get() {
		// Get the data
		if (isset($_COOKIE['PLSESSION'])) {

			self::$_visitorId = $_COOKIE['PLSESSION'];

			$table = self::_getDataTableInstance();
			$select = $table->select()->where('cookie_id = ?', self::$_visitorId);
			self::$_currentRow = $table->fetchRow($select);

			if (is_null(self::$_currentRow)) {
				self::$_userData = new UserShopData();
			} else {
				self::$_userData = @unserialize(self::$_currentRow->serialdata);
			}

		} else {
			self::$_visitorId = uniqid('', true);
			self::$_currentRow = null;
			self::$_userData = new UserShopData();
		}

		
		// Set the cookie to expire in 7 days
		setcookie('PLSESSION', self::$_visitorId, time()+60*60*24*7, '/');

		// Return the data
		return self::$_userData;
	}
	
	public static function save() {
		if (self::$_userData->changed()) {
			if (!is_null(self::$_currentRow)) {
				self::$_currentRow->serialdata = serialize(self::$_userData);
				self::$_currentRow->save();	
			} else {
				$data = array(
				   'cookie_id' => self::$_visitorId,
				   'serialdata' => serialize(self::$_userData)
				);
				$table = self::_getDataTableInstance();
				$table->insert($data);
			}
			self::$_userData->reset();
		}
	}
		
}
