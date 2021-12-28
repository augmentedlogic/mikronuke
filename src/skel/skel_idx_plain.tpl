<?php

require_once '%mn_loader%';

$routing = new \com\augmentedlogic\mikronuke\Routing();
          $routing->add("/", "DefaultHandler");

    $mikronuke = new \com\augmentedlogic\mikronuke\Service($routing);
    $mikronuke->setLogLevel(1);
    $mikronuke->boom();

