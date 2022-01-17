# mikronuke

A minimal web service framework for PHP

## Setup

using composer

```
$ composer require augmentedlogic/mikronuke
```

If you do not want to create the folder structure yourself, run

```
$ php vendor/augmentedlogic/mikronuke/src/setup_project.php
```



## Project Structure

```
composer.json

    /public/
            index.php   - entry point

    /app/
        /app/src/       - handlers and other classes 
        /app/view/      - templates

    /log/               - default log directory

    /vendor/            - created when running composer

```


### Entry Point

```
<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

$routing = new \com\augmentedlogic\mikronuke\Routing();
          $routing->add("/", "DefaultHandler");
          $mikronuke = new \com\augmentedlogic\mikronuke\Service($routing);
          $mikronuke->setLogLevel(1);
          $mikronuke->boom();
```

## Handlers


### A Simple Example Handler

```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;

class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {
          $response->addBody("This is an example.");
          $response->write();
       }

}
```


### Receiving POST and GET Parameter

Note: mikronuke does not distinguish between GET and POST parameters.

```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;

class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {

          // returns the Request Method, e.g. GET, POST etc
          $method = $request->getMethod();

          // extract the full path of the request, e.g. "/api/v1/test"
          $path = $request->getPath();

          // get the raw value
          $param = $request->getParameter("a");

          // get a typed value, returns null if the parameter is not set
          $param1 = $request->getInt("b");
          $param3 = $request->getNumber("d");

          // getString() will return a cleaned (tags stripped) string
          $param2 = $request->getString("c");


          // all of the above methods may also be used with a default value
          // if the parameter is null
          $param1 = $request->getInt("b", 7);

          // get the raw post body, e.g. for json POST data
          $postdata = $request->getPostData();

          $response->addBody("This is an example.");
          $response->write();
       }

}
```


### A Json Response


```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;

class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {
          $response->addBody(json_encode("test", "parameter", "hello"));
          $response->setContentType("application/json");
          $response->write();
       }

}
```

### Reading and Setting Headers




### Handling Cookies

```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;
use \com\augmentedlogic\mikronuke\Cookie;


class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {

          // get the value of a cookie
          $cookie_value = $request->getCookie("firstcookie");

          // set a new cookie
          $c = new Cookie("testcookie");
          $response->addCookie($c->setValue("thisisacookie")
                                 ->setSecure(true)
                                 ->setHttpOnly(true)
                                 ->setDomain("example.com")
                                 ->setExpires(time() + (Cookie::HOUR * 1))
                                 ->setPath("/"));
          // delete a cookie
          $response->delCookie("oldcookie");

      
          $response->addBody("cookie example");
          $response->write();
       }

}
```

### Handling File Uploads

```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;
use \com\augmentedlogic\mikronuke\Cookie;

class UploadHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {
           $f = $request->getFile("myfile");
           if($f->valid()) {
              $size = $f->getSize();
              $mimetype = $f->getMimetype();
              $f->saveTo("/path/to/cache", $f->getName());
              $response->addBody("<h1>File uploaded</h1>");
           } else {
              $response->addBody("<h1>no file attached</h1>");
           }
           $response->write();
     }
}
```


## NanoTemplate - an extremely tiny template library

NanoTemplate is a very simple way to use templates, it supports

* single variables in a template 

* subtemplates

An example template (test.html) looks like this

```

<h1>Hello {$var}</h1>

<p>
Message: {% _subtemp.html %}
</p>

```

and the subtemplate 

```
This is a message for {$person}.
```

The two files (test.html, _subtemp.html) will be located in app/view


Using this in a handler

```
<?php

use \com\augmentedlogic\mikronuke\Request;
use \com\augmentedlogic\mikronuke\Response;
use \com\augmentedlogic\mikronuke\HttpHandler;
use \com\augmentedlogic\mikronuke\NanoTemplate;

class DefaultHandler implements HttpHandler
{

      public function handle(Request $request, Response $response)
      {
          $nt = new NanoTemplate("test.html");
          $nt->set("var", "World");
          $nt->set("person", "you");
          $html = $nt->render();

          $response->addBody($html);
          $response->write();
       }

```

## Server Setup

For Apache with mod-php

```
<VirtualHost *:80>
        ServerName example.com
        DocumentRoot /path/to/my/public

        <Directory /path/to/my/public>
          RewriteEngine on
          RewriteCond %{REQUEST_FILENAME} !-d
          RewriteCond %{REQUEST_FILENAME} !-f
          RewriteRule . /index.php [L]
          AllowOverride All
          Require all granted
        </Directory>
</VirtualHost>

```

For Nginx with php-fpm

```
server {

    root /path/to/public;
    server_name example.com;

    try_files $uri $uri/ /index.php?$args;

    location ~ \.php$ {
            include /etc/nginx/fastcgi_params;
            fastcgi_index index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root/index.php;
            fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
    }

}
```
