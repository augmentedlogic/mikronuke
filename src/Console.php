<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Console
{
    const BLACK = 30;
    const RED = 31;
    const GREEN = 32;
    const YELLOW = 33;
    const BLUE = 34;
    const PURPLE = 35;
    const CYAN = 36;
    const WHITE = 37;

    private $argv = array();

    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    public function getArgument(string $arg, mixed $default_value = null): ?string
    {
        foreach ($this->argv as $i => $a) {
            if ($a == $arg) {
                if (isset($argv[$i + 1])) {
                    return $argv[$i + 1];
                } else {
                    return $default_value;
                }
            }
        }
    }

    public static function print(?string $msg, int $color = 0, bool $bold = false): void
    {
        if ($bold == true) {
            print "\e[1;{$color}m{$msg}\e[0m";
        } else {
            print "\e[0;{$color}m{$msg}\e[0m";
        }
    }

    public static function println(?string $msg, int $color = 0, bool $bold = false): void
    {
        if ($bold == true) {
            print "\e[1;{$color}m{$msg}\e[0m\n\r";
        } else {
            print "\e[0;{$color}m{$msg}\e[0m\n\r";
        }
    }
}
