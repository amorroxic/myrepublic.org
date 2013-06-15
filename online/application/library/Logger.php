<?php

class Logger
{
    private static $_logs = array();
    
    private static $_logId;
    
    private static $_configuration;
    
    public static function get($logName = 'general')
    {
        if (!self::$_logId) {
            self::$_logId = uniqid('', true);

            // Load the configuration file
            self::$_configuration = require(ROOTDIR . '/configuration/logging.conf.php');
        }
        if (!array_key_exists($logName, self::$_logs)) {
            
            // Initialize the formatter
            $format = '%timestamp% %priorityName% (%priority%) [%ip%] %logid%: %message%' . PHP_EOL;
            $formatter = new Zend_Log_Formatter_Simple($format);

            // Initialize the log
            $logger = new Zend_Log();
            $logger->setEventItem('logid', self::$_logId);
            $logger->setEventItem('ip', $_SERVER['REMOTE_ADDR']);
                        
            // Initialize the file writer
            if (self::$_configuration['file']['enabled']) {
                $writer = new Zend_Log_Writer_Stream(ROOTDIR . '/logs/' . $logName . '.log');
                $writer->addFilter(self::$_configuration['file']['logLevel']); 
                $writer->setFormatter($formatter);
                $logger->addWriter($writer);
            }

            // Initialize the email writer  
            if (self::$_configuration['email']['enabled']) {
	            // Get the email configuration
	            $emailConfiguration = self::$_configuration['email'];

	            // Set up a Zend_Mail object
	            $mail = new Zend_Mail(); 
                if ($emailConfiguration['smtpServer']) {
                    $mail->setDefaultTransport(new Zend_Mail_Transport_Smtp($emailConfiguration['smtpServer']));
                }
                $mail->setFrom($emailConfiguration['fromAddress'], $emailConfiguration['fromName']); 
                $mail->addTo($emailConfiguration['toAddress'], $emailConfiguration['toName']);
                
                // Initialize the writer
                $writer = new LB_Log_Writer_Email($mail); 
                $writer->setSubjectPrependText($emailConfiguration['subjectPrefix']); 
                $writer->addFilter($emailConfiguration['logLevel']); 
                $writer->setFormatter($formatter);
                $logger->addWriter($writer);
            }

            // Initialize the firebug writer
            if (self::$_configuration['firebug']['enabled']) {
                $writer = new Zend_Log_Writer_Firebug();
                $writer->addFilter(self::$_configuration['firebug']['logLevel']); 
                $logger->addWriter($writer);
            }
            
            // Add the log to the array
            self::$_logs[$logName] = $logger;
        }
        return self::$_logs[$logName];    
    }    
}