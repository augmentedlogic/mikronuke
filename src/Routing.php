<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class Routing
{
    private $routes = array();
    private $redirects = array();


    public function add(String $path, String $handler)
    {
        $this->routes[] = array("path" => $path, "handler" => $handler);
    }

    public function redirect(String $path, String $newpath, int $response_code = 302)
    {
        $this->redirects[] = array("path" => $path, "newpath" => $newpath, "response_code" => $response_code);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getRedirects()
    {
        return $this->redirects;
    }

}


