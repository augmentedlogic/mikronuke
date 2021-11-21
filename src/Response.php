<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class Response
{

    private $headers_a = array();
    private $body_a = array();
    private $status_code = 200;
    private $cookies = array();

    public function addHeader($k, $v)
    {
        $this->headers_a[$k] = $v;
    }

    public function setContentType($v)
    {
        $this->headers_a['Content-Type'] = $v;
    }

    public function setStatusCode(int $status_code)
    {
        $this->status_code = $status_code;
    }

    public function addCookie($name, $value = "", $expires = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        $this->cookies[$name] = array("value" => $value, "expire" => $expires, "path" => $path, "domain" => $domain, "secure" => $secure, "httponly" => $httponly);
    }


    public function delCookie($name)
    {
        setcookie($name, "", time() - 86000);
    }


    public function addBody($s)
    {
        $this->body_a[] = $s;
    }

    public function write()
    {
        http_response_code($this->status_code);
        foreach($this->cookies as $name=>$v) {
            setcookie($name, $v['value'], $v['expires'], $v['path'], $v['domain'], $v['secure'], $v['httponly']);
        }

        foreach($this->headers_a as $k=>$v) {
            header($k.": ".$v);
        }

        print implode ("\n", $this->body_a);
        flush();
    }



}

