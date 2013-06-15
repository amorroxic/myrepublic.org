<?php

class Authentication_Manager
{
	
	protected static $username;
	protected static $password;
	protected static $authTable;
	
	protected static $allowed;
	protected static $message;
	
	public static function perform($options = array()) {

		self::$username 	= $options["user"];
		self::$password 	= $options["pass"];
		self::$authTable 	= $options["table"];
		self::$allowed		= false;
		self::$message		= "";

        Zend_Loader::loadClass('Zend_Filter_StripTags');
        $filter = new Zend_Filter_StripTags();
        $user = $filter->filter(self::$username);
        $pass = $filter->filter(self::$password);

        if (empty($user)) {
            self::$allowed = false;
            self::$message = 'Utilizator incorect';
            return self::$allowed;
        }

        if (empty($pass)) {
            self::$allowed = false;
            self::$message = 'Introduceti o parola';
            return self::$allowed;
        }    
        
        Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
        $dbAdapter = Zend_Registry::get('dbAdapter');
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName(self::$authTable);
        $authAdapter->setIdentityColumn('email');
        $authAdapter->setCredentialColumn('password');
        
        // Set the input credential values to authenticate against
        $authAdapter->setIdentity($user);
        $authAdapter->setCredential(md5($pass));
        
        // do the authentication 
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            // success : store database row to auth's storage system
            // (not the password though!)
            $data = $authAdapter->getResultRowObject(null, 'password');
            $auth->getStorage()->write($data);
            self::$allowed = true;
        } else {
            // failure: clear database row from session
            self::$message = 'Login incorect';
            self::$allowed = false;
        }
        
	    return self::$allowed;
		
	}
	
	public static function isAllowed() {
	    $auth = Zend_Auth::getInstance();
        return $auth->hasIdentity();
	}
	
	public static function isAdminAllowed() {
	    $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	if ($auth->getStorage()->read()->site_role == "admin") return true;
        }
        return false; 
	}

	public static function me() {
	    $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
        	return $auth->getStorage()->read();
        }
        return null; 
	}

	
	public static function getErrorMessage() {
		return self::$message;
	}

    public static function logout()
    {
        Zend_Auth::getInstance()->clearIdentity();
        self::$allowed = false;
    }
	
}

?>