<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

use \com\augmentedlogc\mikronuke\Routing;
use \com\augmentedlogc\mikronuke\Toolkit;


class Service {

    // settings
    private $namespace = NULL;
    private $setting_base_dir = "";
    private $setting_debugger = false;
    private $setting_app_dir = null;
    private $setting_log_dir = null;
    private $routes = array();
    private $redirects = array();
    private $url_array = array();
    private $context = array("server" => array(),
                             "headers" => array(),
                             "cookies" => array(),
                             "files" => array(),
                             "parameters" => array());

    private function benchmark()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    function __construct($routing) {
        $this->routes = $routing->getRoutes();
        $this->redirects = $routing->getRedirects();
        $this->b_start = $this->benchmark();
        $this->setting_base_dir = getcwd();
        $this->setting_app_dir = str_replace("/public", "", $this->setting_base_dir)."/app/src";
        $this->setting_log_dir = str_replace("/public", "", $this->setting_base_dir)."/log";
    }

    private function startsWith($path, $url_path)
    {
        $len = strlen($url_path);
        return (substr($path, 0, $len) === $url_path);
    }

    public function showDebugConsole($b)
    {
        $this->setting_debugger = $b;
    }


    public function setAppDir($path)
    {
        $this->setting_app_dir = $path;
    }

    public function setSourceDir($path)
    {
        $this->setting_app_dir = $path;
    }


    public function setLogDir($dir)
    {
        define("MN_LOG_DIR", $dir);
    }

    public function setTemplateDir($path)
    {
        define("MN_TEMPLATE_DIR", $path);
    }


    public function setLogLevel($level)
    {
        define("MN_LOG_LEVEL", $level);
    }


    public function extract_url_parameters(string $urlpath, string $config_path)
    {

        $path_parts = explode("/" , $config_path );
        $url_parts = explode("/" , $urlpath);
        $this->url_array = array();
        $i =0;
        foreach($path_parts as $u) {
            if(preg_match_all('/({+.*?})/', $u, $matches)) {
                $param = str_replace(array("{","}"), "", $matches[0][0]);
                $this->url_array[$param] = $url_parts[$i];
            }
            $i++;
        }

    }


    private function get_routing_path(string $path): string
    {
        return preg_replace('/({+.*?})/', '*', $path);
    }


    private function getRequestHeaders() {

    }

    private function decideRedirect($url_path)
    {
        foreach($this->redirects as $redirect) {
            if($url_path == $redirect['path']) {
                return $redirect;
            }
        }
        return array();
    }

    private function log($msg, $level = 1)
    {
        file_put_contents(constant("MN_LOG_DIR")."/mn.log", date("Y M j G:i:s", time())." ".$msg."\n", FILE_APPEND);
    }


    private function decideRoute(string $path)
    {
        foreach($this->routes as $routes) {
            $path_to_route = $this->get_routing_path($routes['path']);
            if(fnmatch($path_to_route, $path) ) {
                if(fnmatch("*{*}*" , $routes['path']) ) {
                    $this->extract_url_parameters($path, $routes['path']);
                }
                return $routes['handler'];
            }
        }
        return NULL;
    }

    private function debugger()
    {
        $path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        print "<div style='background: #ddd; with: 100%; padding: 20px;'>";
        print "<h3>Debugger</h3>";
        print "<pre>";
        print_r($this->routes);
        print_r($this->redirects);
        print "</pre>";
        print "Choosen Handler: ".$this->decideRoute($path_only);
        print "<h4>Context</h4>";
        print "<pre>";
        print_r($this->context);
        print "</pre>";
        print "<div>";
    }

    private function populateContext()
    {
        $headers = array();
        $servers = array();
        foreach($_SERVER as $key => $value) {
            $tkey = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $servers[$key] = strip_tags(preg_replace( "/\r|\n/", "", trim($value, "\n")));
        }

        foreach(getallheaders() as $header => $value) {
            $headers[strtolower($header)] = strip_tags($value);
        }

        $this->context['server'] = $servers;
        $this->context['headers'] = $headers;

        foreach($this->url_array as $k=>$v) {
            $this->context['parameters'][$k] = $v;
        }
        foreach($_GET as $k=>$v) {
            $this->context['parameters'][$k] = $v;
        }
        foreach($_POST as $k=>$v) {
            $this->context['parameters'][$k] = $v;
        }
        foreach($_COOKIE as $k=>$v) {
            $this->context['cookies'][$k] = $v;
        }
        foreach($_FILES as $k=>$v) {
            $this->context['files'][$k] = $v;
        }

        $this->context['method'] = $_SERVER['REQUEST_METHOD'];
        if($this->context['method'] == "POST") {
           $this->context['rawpost'] = file_get_contents("php://input");
        }
    }

    public function detonate()
    {
          $this->boom();
    }

    public function boom()
    {
          $install_dir = dirname(debug_backtrace()[0]['file'], 2);
          if(!isset($this->setting_app_dir)) {
             $this->setting_app_dir = $install_dir."/app/src";
          }

          if(!defined("MN_TEMPLATE_DIR")) {
             define("MN_TEMPLATE_DIR", $install_dir."/app/view");
          }

          if(!defined("MN_LOG_DIR")) {
             define("MN_LOG_DIR", $install_dir."/log");
          }


        $b_start = microtime(true);
        $path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($path_only != "/") {
            $path_only = rtrim($path_only,"/");
        }

        $redirect_to = $this->decideRedirect($path_only);
        if(isset($redirect_to['newpath'])) {
            $this->log("redirecting {$path_only } to {$redirect_to['newpath']} {$redirect_to['response_code']}", 1);
            http_response_code($redirect_to['response_code']);
            header("Location: ".$redirect_to['newpath']);
            exit();
        }

        $handler = $this->decideRoute($path_only);
        if(!$handler) {
            http_response_code(404);
            print "Not found";
        } else {
            $this->populateContext();
            $request = new \com\augmentedlogic\mikronuke\Request();
            $response = new \com\augmentedlogic\mikronuke\Response();
            $request->setContext($this->context);
            require_once $this->setting_app_dir.'/'.$handler.'.php';
            $Instance = new $handler;
            $Instance->handle($request, $response);
            $b_time = round($this->benchmark() - $this->b_start, 4);
            $this->log($handler ." => ". $b_time, 1);
            if($this->setting_debugger) {
                $this->debugger();
            }

        }
    }

}
