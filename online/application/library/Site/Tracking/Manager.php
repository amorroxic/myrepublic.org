<?php

class Site_Tracking_Manager 
{
    public static function getTrackingCode() 
    {
        return file_get_contents(ROOTDIR . '/configuration/tracking.conf');
    }
    	
}