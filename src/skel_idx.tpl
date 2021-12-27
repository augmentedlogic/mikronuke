<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

$routing = new \com\augmentedlogic\mikronuke\Routing();
          $routing->add("/", "DefaultHandler");

    $mikronuke = new \com\augmentedlogic\mikronuke\Service($routing);

    $mikronuke->setLogDir(dirname(__DIR__)."/log");
    $mikronuke->setAppDir(dirname(__DIR__)."/app/src");
    $mikronuke->setTemplateDir(dirname(__DIR__)."/app/view");
    $mikronuke->setLogLevel(1);
    $mikronuke->detonate();
