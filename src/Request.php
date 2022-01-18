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

    public function getCookie($key) : ?string
    {
        if(isset($this->context['cookies'][$key])) {
           return $this->context['cookies'][$key];
        } else {
           return null;
        }
    }

    public function getPath(): string
    {
        return strtok($_SERVER["REQUEST_URI"], '?');
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


    public function getParameter($k, string $def = null): ?string
    {
        if(isset($this->context['parameters'][$k])) {
            return $this->context['parameters'][$k];
        } else {
            return $def;
        }
    }

    public function getArray($k, array $def = null) : ?array
    {
        if(isset($this->context['parameters'][$k])) {
          return (array) $this->context['parameters'][$k];
        } else {
          return $def;
        }
    }


    public function getNumber($k, $def = null)
    {
        $i = $this->parseNumber($this->context['parameters'][$k]);
        if(is_null($i)) {
            return $def;
        }
        return $i;
    }

    public function getInt($k, int $def = null) : ?int
    {
        $i = $this->parseInt($this->context['parameters'][$k]);
        if(is_null($i)) {
            return $def;
        }
        return $i;
    }

    public function getString($k, string $def = null) : ?string
    {
        if(isset($this->context['parameters'][$k])) {
          return (String) strip_tags($this->context['parameters'][$k]);
        } else {
          return $def;
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

