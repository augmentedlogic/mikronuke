<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

    $routing = new \com\augmentedlogic\mikronuke\Routing();
    $routing->add("/", "DefaultHandler");


    $service = new \com\augmentedlogic\mikronuke\Service($routing);

    $service->loadApp('\\com\\example\\website', dirname(__FILE__).'/../app/src')
             ->setLogLevel(1)
             ->boom();
