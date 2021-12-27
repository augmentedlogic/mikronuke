<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class NanoTemplate
{
    private $keys = array();
    private $values = array();
    private $filename = "";

    function __construct($filename) {
        $this->filename = $filename;
    }

    private function findSubtemplates($main_tpl)
    {
        preg_match_all("#{% (.+?) %}#is",$main_tpl, $matches);
        return $matches;
    }

    public function set($k, $v) : void
    {
        $this->keys[] = $k;
        $this->values[] = $v;
    }

    public function render() : string
    {
        $main_tpl = file_get_contents(MN_TEMPLATE_DIR."/".$this->filename);

        $subtemplates = $this->findSubtemplates($main_tpl);
        if(isset($subtemplates[1])) {
            foreach($subtemplates[1] as $subtemplate) {
                $tpl = "";
                $tpl = file_get_contents(MN_TEMPLATE_DIR."/".$subtemplate);
                $main_tpl = str_replace("{% ".$subtemplate." %}", $tpl, $main_tpl);
            }
        }

        foreach($this->keys as $i=>$k) {
            $this->keys[$i] = '{$'.$k.'}';
            }
            $html = str_replace($this->keys, $this->values, $main_tpl);

            return $html;
        }


    }

