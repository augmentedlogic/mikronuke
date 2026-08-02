<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Response
{
    private array $headers_a = array();
    private array $body_a = array();
    private int $status_code = 200;
    private array $cookies = array();
    private bool $disable_buffer = false;
    private ?string $out = null;

    // common types
    const string TYPE_JSON = 'application/json';
    const string TYPE_TEXT = 'text/plain';
    const string TYPE_XML = 'application/xml';
    const string TYPE_HTML = 'text/html';
    // common status codes
    const int OK = 200;
    const int CREATED = 201;
    const int ACCEPTED = 202;
    const int MOVED_PERMANENTLY = 301;
    const int BAD_REQUEST = 400;
    const int UNAUTHORIZED = 401;
    const int FORBIDDEN = 403;
    const int NOT_FOUND = 404;
    const int METHOD_NOT_ALLOWED = 405;
    const int URI_TOO_LONG = 414;
    const int INTERNAL_SERVER_ERROR = 500;
    const int SERVICE_UNAVAILABLE = 503;

    function __construct() {}

    public function addHeader(string $k, $v): void
    {
        $this->headers_a[$k] = $v;
    }

    public function setContentType(string $v): void
    {
        $this->headers_a['Content-Type'] = $v;
    }

    public function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    #[\Deprecated(message: 'use the addCookie instead', since: '0.2.1')]
    public function addCookieLegacy(string $name, string $value, int $expires = 0, string $path = '', string $domain = '', bool $secure = false, bool $httponly = false): void
    {
        $this->cookies[$name] = array('value' => $value, 'expires' => $expires, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly);
    }

    public function addCookie(Cookie $c): void
    {
        $this->cookies[$c->getName()] = array('value' => $c->getValue(),
            'expires' => $c->getExpire(),
            'path' => $c->getPath(),
            'domain' => $c->getDomain(),
            'secure' => $c->getSecure(),
            'httponly' => $c->getHttpOnly(),
            'SameSite' => $c->getSameSite());
    }

    public function delCookie(string $name): void
    {
        setcookie($name, '', time() - 172000);
    }

    public function addBody(string $s): void
    {
        $this->body_a[] = $s;
    }

    public function disable_buffer(): void
    {
        print $disable_buffer = true;
    }

    public function out(): void
    {
        print $this->out;
        flush();
    }

    public function write(): void
    {
        http_response_code($this->status_code);
        foreach ($this->cookies as $name => $v) {
            $value = $v['value'];
            unset($v['value']);
            setcookie($name, $value, $v);
        }

        foreach ($this->headers_a as $k => $v) {
            header($k . ': ' . $v);
        }

        $this->out = implode("\n", $this->body_a);
        flush();
    }
}
