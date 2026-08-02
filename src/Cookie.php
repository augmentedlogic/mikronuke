<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Cookie
{
    public const DAY = 86000;
    public const MIN = 60;
    public const HOUR = 3600;

    private ?string $name = null;
    private ?string $value = null;
    private bool $secure = false;
    private int $expire = 0;
    private int $maxage = 0;
    private bool $httponly = true;
    private string $path = '/';
    private string $same_site = 'Strict';
    private string $domain = '';

    function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setValue(string $value): Cookie
    {
        $this->value = $value;
        return $this;
    }

    public function setExpires(int $t): Cookie
    {
        $this->expire = $t;
        return $this;
    }

    public function setMaxAge(int $t): Cookie
    {
        $this->expire = time() + $t;
        return $this;
    }

    public function setSecure(bool $b = false): Cookie
    {
        $this->secure = $b;
        return $this;
    }

    public function setHttpOnly(?bool $b = true): Cookie
    {
        $this->httponly = $b;
        return $this;
    }

    public function setPath(string $p = '/'): Cookie
    {
        $this->path = $p;
        return $this;
    }

    public function setDomain(string $d = ''): Cookie
    {
        $this->domain = $d;
        return $this;
    }

    public function setSameSite(string $s = 'Strict'): Cookie
    {
        $this->same_site = $s;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getExpire(): ?int
    {
        return $this->expire;
    }

    public function getSecure(): ?bool
    {
        return $this->secure;
    }

    public function getHttpOnly(): bool
    {
        return $this->httponly;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxage;
    }

    public function getSameSite(): ?string
    {
        return $this->same_site;
    }
}
