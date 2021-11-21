<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class Request
{

    private $context = array( "headers" => array() );

    private function parseInt($s) {
        if(ctype_digit($s)) {
            return (int) $s;
        } else {
            return NULL;
        }
    }

    private function parseNumber($s) {
        if(is_numeric($s)) {
            return (float) $s;
        } else {
            return NULL;
        }
    }

    public function setContext($params)
    {
        $this->context = $params;
    }

    public function getCookie($key, $filter = FILTER_SANITIZE_STRING) : ?string
    {
        return $this->context['cookies'][$key];
    }


    public function getFile($key) : ReceivedFile
    {
        return new ReceivedFile($this->context['files'][$key]);
    }

    public function getMethod() : string
    {
        return $this->context['method'];
    }

    public function getPostData() : ?string
    {
        return $this->context['rawpost'];
    }


    public function getParameter($k, $filter = FILTER_SANITIZE_STRING) : ?mixed
    {
        return $this->context['parameters'][$k];
    }

    public function getInt($k) : ?int
    {
        return $this->parseInt($this->context['parameters'][$k]);
    }

    public function getNumber($k)
    {
        return $this->parseNumber($this->context['parameters'][$k]);
    }

    public function getNumberWithDefault($k, $default_value)
    {
        $i = $this->parseNumber($this->context['parameters'][$k]);
        if(is_null($i)) {
            return $default_value;
        }
        return $i;
    }

    public function getIntWithDefault($k, $default_value) : int
    {
        $i = $this->parseInt($this->context['parameters'][$k]);
        if(is_null($i)) {
            return $default_value;
        }
        return $i;
    }

    public function getString($k) : ?string
    {
        if(isset($this->context['parameters'][$k])) {
          return (String) strip_tags($this->context['parameters'][$k]);
        } else {
          return null;
        }
    }


    public function getHeader($k) : ?string
    {
        if(isset($this->context["headers"][strtolower($k)])) {
            return $this->context["headers"][strtolower($k)];
        }
        return NULL;
    }

}

