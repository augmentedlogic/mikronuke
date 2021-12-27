<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

$routing = new \com\augmentedlogic\mikronuke\Routing();
          $routing->add("/", "DefaultHandler");

    $mikronuke = new \com\augmentedlogic\mikronuke\Service($routing);
    $mikronuke->setLogLevel(1);
    $mikronuke->boom();
