<?php

return array(
	
	'file' => array(
                   'enabled'       => true,
                   'logLevel'      => Zend_Log::INFO
               ),

	'email' => array(
                   'enabled'       => false,
    		       'fromName'      => 'System',
                   'fromAddress'   => 'amo@nouauzina.ro',
                   'toName'        => 'Logging',
                   'toAddress'     => 'amo@nouauzina.ro',
                   'smtpServer'    => null,
                   'subjectPrefix' => 'ERRORS',
                   'logLevel'      => Zend_Log::ERR
               ),
	
	'firebug' => array(
                   'enabled'       => true,
                   'logLevel'      => Zend_Log::DEBUG
               )
);
