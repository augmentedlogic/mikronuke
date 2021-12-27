<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;
use \com\augmentedlogic\mikronuke\Toolkit;

class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {
          $response->addBody("<h1>Boom! It's working!</h1>");
          $response->write();
      }

}

