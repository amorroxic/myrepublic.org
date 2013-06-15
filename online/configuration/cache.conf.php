<?php

return array(
    'frontend'        => 'Core',
    'backend'         => 'File',
    'frontendOptions' => array(
       'lifetime'                => null, 
       'automatic_serialization' => false
    ),
    
    'backendOptions' => array(
        'cache_dir' => ROOTDIR . '/cache/' 
    )
);