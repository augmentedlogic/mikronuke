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
class TwigWrap
{
    private $twig = null;
    private $template = null;
    private $parameters = array();

    function __construct($template) {
        $this->template = $template;
        if (!defined('MN_TWIG_VIEW_DIR')) {
            define("MN_TWIG_VIEW_DIR", dirname(__FILE__).'/../../../../app/view/');
        }
        if (!defined('MN_TWIG_CACHE_DIR')) {
            define('MN_TWIG_CACHE_DIR', false);
        }

        $loader = new \Twig\Loader\FilesystemLoader(MN_TWIG_VIEW_DIR);
        $twig = new \Twig\Environment($loader, ['cache' => MN_TWIG_CACHE_DIR,]);
        $this->twig = $twig;
    }


    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }


    public function render() {
        return $this->twig->render($this->template, $this->parameters);
    }

}
