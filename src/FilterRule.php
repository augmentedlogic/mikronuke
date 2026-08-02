<?php

/**
 * @copyright 2026 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

/**
 * this class is currently experimental
 */
class FilterRule
{
    private bool $validated = true;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function mustHave(array $params): bool
    {
        foreach ($params as $p) {
            if (isset($this->request->getContext()['parameters'][$p])) {
                $par = $this->request->getContext()['parameters'][$p];
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

    public function numberWithinRange(string $key, float $min, float $max = INF): bool
    {
        if (isset($this->request->getContext()['parameters'][$key])) {
            $par = $this->request->getContext()['parameters'][$key];
            if (is_numeric($par)) {
                if ($par >= $min && $this->request->getContext()['parameters'][$key] <= $max) {
                    print 'in range';
                    return true;
                }
                print 'not in range';
            }
        }
        $this->validated = false;
        return false;
    }

    public function stringWithinRange(string $key, int $min, $max = INF): bool
    {
        if (isset($this->request->getContext()['parameters'][$key])) {
            $par = $this->request->getContext()['parameters'][$key];
            if (mb_strlen($par, 'UTF-8') >= $min && mb_strlen($par, 'UTF-8') <= $max) {
                return true;
            } else {
                $this->validated = false;
            }
        }
        return false;
    }

    public function validate(): bool
    {
        return $this->validated;
    }
}
