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

    private array $argv = array();
    private ?string $load_prefix = null;
    private ?string $load_src_dir = null;

    private function autoloader($class)
    {
        $prefix = $this->load_prefix;
        $base_dir = $this->load_src_dir;

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }

    public function loadApp($load_prefix, $load_src_dir = null)
    {
        $this->load_prefix = $load_prefix;
        $this->load_src_dir = $load_src_dir;
        spl_autoload_register([$this, 'autoloader']);
    }

    public function __construct($argv = null)
    {
        if ($argv != null) {
            $this->argv = $argv;
        }
    }

    public function setLogLevel(int $log_level)
    {
        define('MN_LOG_LEVEL', $log_level);
    }

    public function getArgument(string $arg, mixed $default_value = null): mixed
    {
        foreach ($this->argv as $i => $a) {
            if ($a == $arg) {
                if (isset($this->argv[$i + 1])) {
                    return $this->argv[$i + 1];
                } else {
                    return $default_value;
                }
            }
        }
        return $default_value;
    }

    public function hasArgument(string $arg, bool $default_value = false): bool
    {
        foreach ($this->argv as $i => $a) {
            if ($a == $arg) {
                return true;
            }
        }
        return $default_value;
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
            print "\e[1;{$color}m{$msg}\e[0m" . PHP_EOL;
        } else {
            print "\e[0;{$color}m{$msg}\e[0m" . PHP_EOL;
        }
    }
}
