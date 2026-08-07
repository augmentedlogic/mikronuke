<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Routing
{
    private array $routes = array();
    private array $redirects = array();
    private ?string $namesp = null;
    private bool $automatic_loading = false;

    #[\Deprecated(message: 'use the Service class instead', since: '0.2.1')]
    public function loadApp($namesp = null, $automatic_loading = false): void
    {
        $this->namesp = $namesp;
        $this->automatic_loading = $automatic_loading;
    }

    public function add(String $path, String $handler): void
    {
        $this->routes[] = array('path' => $path, 'handler' => $handler);
    }

    public function redirect(String $path, String $newpath, int $response_code = 302): void
    {
        $this->redirects[] = array('path' => $path, 'newpath' => $newpath, 'response_code' => $response_code);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getNamespace(): ?string
    {
        return $this->namesp;
    }

    public function getAutomaticLoading(): bool
    {
        return $this->automatic_loading;
    }

    public function getRedirects(): array
    {
        return $this->redirects;
    }
}
