<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Request
{
    private array $context = array('headers' => array(), 'bearer_token' => null);
    private bool $validated = true;
    private bool $striptags = true;

    public function mustHave(array $params): bool
    {
        foreach ($params as $p) {
            if (isset($this->context['parameters'][$p])) {
                $par = $this->context['parameters'][$p];
                if (empty(trim($par))) {
                    $this->validated = false;
                    return false;
                }
            } else {
                $this->validated = false;
                return false;
            }
        }

        return true;
    }

    private function parseInt(?string $s): ?int
    {
        if (ctype_digit($s)) {
            return (int) $s;
        } else {
            return NULL;
        }
    }

    public function getContext(): array
    {
        return $this->context;
    }

    private function parseNumber(?string $s): mixed
    {
        if (is_numeric($s)) {
            return (float) $s;
        } else {
            return NULL;
        }
    }

    public function setContext(array $params): void
    {
        $this->context = $params;
    }

    public function getCookie(string $key): ?string
    {
        if (isset($this->context['cookies'][$key])) {
            return $this->context['cookies'][$key];
        } else {
            return null;
        }
    }

    public function getRequestCookie(string $key): ?Cookie
    {
        if (isset($this->context['cookies'][$key])) {
            $c = new Cookie($key);
            $c->setValue($this->context['cookies'][$key]);
            return $c;
        } else {
            return null;
        }
    }

    public function getPath(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function getFile($key): ?ReceivedFile
    {
        if (!isset($this->context['files'][$key]['name']) || empty($this->context['files'][$key]['name'])) {
            return null;
        }
        return new ReceivedFile($this->context['files'][$key]);
    }

    public function getMethod(): string
    {
        return $this->context['method'];
    }

    public function getPostData(): ?string
    {
        return $this->context['rawpost'];
    }

    public function getParameter(string $k, ?string $def = null): ?string
    {
        if (isset($this->context['parameters'][$k])) {
            return $this->context['parameters'][$k];
        } else {
            return $def;
        }
    }

    public function getArray(string $k, ?array $def = null): ?array
    {
        if (isset($this->context['parameters'][$k])) {
            return (array) $this->context['parameters'][$k];
        } else {
            return $def;
        }
    }

    public function getNumber(string $k, mixed $def = null): ?string
    {
        $i = $this->parseNumber($this->context['parameters'][$k]);
        if (is_null($i)) {
            return $def;
        }
        return $i;
    }

    public function getInt(string $k, ?int $def = null): ?int
    {
        $i = $this->parseInt($this->context['parameters'][$k]);
        if (is_null($i)) {
            return $def;
        }
        return $i;
    }

    public function getString(string $k, ?string $def = null): ?string
    {
        if (isset($this->context['parameters'][$k])) {
            return (string) strip_tags($this->context['parameters'][$k]);
        } else {
            return $def;
        }
    }

    public function getHeader(string $k): ?string
    {
        if (isset($this->context['headers'][strtolower($k)])) {
            return $this->context['headers'][strtolower($k)];
        }
        return NULL;
    }

    public function getBearerToken(): ?string
    {
        return $this->context['bearer_token'];
    }

    public function parseBearerToken(): bool
    {
        foreach ($this->context['headers'] as $h) {
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    $this->context['bearer_token'] = $matches[1];
                    return true;
                }
            }
        }
        return false;
    }
}
