<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class LogTool
{
    const ALL = 0;
    const DEBUG = 1;
    const INFO = 2;
    const ESSENTIAL = 3;

    private ?string $seg = null;
    private string $logfile = 'service';
    private int $log_level = 2;
    private ?string $tag = null;

    function __construct(string $logtarget = 'service')
    {
        $this->logfile = $logtarget;

        if (!defined('MN_LOG_LEVEL')) {
            define('MN_LOG_LEVEL', 1);
        }

        if (!defined('MN_LOG_DATE_FORMAT')) {
            define('MN_LOG_DATE_FORMAT', 'Y M j G:i:s');
        }
    }

    public static function setLogDir(string $logdir): void
    {
        define('MN_LOG_DIR', $logdir);
    }

    public static function enableBenchmarkLog(bool $enable): void
    {
        define('MN_ENABLE_BENCHMARK_LOG', $enable);
    }

    public static function configureLogDateFormat(string $date_format): void
    {
        define('MN_LOG_DATE_FORMAT', $date_format);
    }

    public function tag(string $tag): LogTool
    {
        $this->tag = $tag;
        return $this;
    }

    public static function log($msg, $level = 1, $filename = 'app.log')
    {
        if (!defined('MN_LOG_LEVEL')) {
            define('MN_LOG_LEVEL', 1);
        }
        if ($level >= MN_LOG_LEVEL) {
            file_put_contents(MN_LOG_DIR . '/' . $filename, date('Y M j G:i:s', time()) . ' ' . $msg . "\n", FILE_APPEND);
        }
    }

    public function write(string $msg, $level = 1): void
    {
        if ($level >= MN_LOG_LEVEL) {
            $line = date('Y M j G:i:s', time()) . ' ' . $msg . "\n";
            if ($this->tag !== null) {
                $line = date('Y M j G:i:s', time()) . ' [' . $this->tag . '] ' . $msg . "\n";
            }
            $file = fopen(MN_LOG_DIR . '/' . $this->logfile . '.log', 'a') or die('Unable to open logfile!');
            fwrite($file, $line);
            fclose($file);
            $this->tag = null;
        }
    }
}
