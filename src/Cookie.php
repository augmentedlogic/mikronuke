<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class Cookie
{

    public const DAY = 86000;
    public const MIN = 60;
    public const HOUR = 3600;


    private $name;
    private $value;
    private $secure = false;
    private $expire = 0;
    private $maxage = 0;
    private $httponly = false;
    private $path = "";
    private $domain = "";

    function __construct($name)
    {
        $this->name = $name;
    }

    public function setValue($value)
    {
       $this->value = $value;
       return $this;
    }

    public function setExpires($value)
    {
       $this->expire = $value;
       return $this;
    }

    public function setMaxAge($t)
    {
       $this->maxage = $t;
       return $this;
    }


    public function setSecure($b = false)
    {
       $this->secure = $b;
       return $this;
    }

    public function setHttpOnly($b = false)
    {
       $this->httponly = $b;
       return $this;
    }

    public function setPath($p = "")
    {
       $this->path = $p;
       return $this;
    }

    public function setDomain($d = "")
    {
       $this->domain = $d;
       return $this;
    }



    public function getName()
    {
       return $this->name;
    }

    public function getValue()
    {
       return $this->value;
    }

    public function getExpire()
    {
       return $this->expire;
    }

    public function getSecure()
    {
       return $this->secure;
    }

    public function getHttpOnly()
    {
       return $this->httponly;
    }

    public function getPath()
    {
       return $this->path;
    }

    public function getDomain()
    {
       return $this->domain;
    }

    public function getMaxAge()
    {
       return $this->maxage;
    }

}
