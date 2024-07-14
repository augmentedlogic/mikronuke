<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

/**
 *  This class is experimental
 **/
class LogTool
{

    private $seg = null;
    private $logfile = "service";


    function __construct($logtarget = "service")
    {
        $this->logfile = $logtarget;
        if(!defined("MN_LOG_DATE_FORMAT")) {
            define("MN_LOG_DATE_FORMAT", "Y M j G:i:s");
        }
    }

    public static function configureLogDateFormat($date_format)
    {
        define("MN_LOG_DATE_FORMAT", $date_format);
    }


    public function tag($tag)
    {
        $this->tag = $tag;
        return $this;

    }

    public function log($msg)
    {
        //if($level <= MN_LOG_LEVEL) {
        $line = date("Y M j G:i:s", time())." ".$msg."\n";
        if($this->tag) {
            $line = date("Y M j G:i:s", time())." [". $this->tag ."] ".$msg."\n";
        }
        $file = fopen(MN_LOG_DIR."/". $this->logfile.".log", "a") or die("Unable to open logfile!");
        fwrite($file, $line);
        fclose($file);
        $this->tag = null;
        //}
    }

}

