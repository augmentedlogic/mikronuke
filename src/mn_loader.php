<?php

/**
 * This autoloadder is only for cases where mikronuke is used without composer,
 * otherwise ignore
 */
spl_autoload_register(function ($class_name) {
    $class_name = str_replace('com\\augmentedlogic\\mikronuke\\', '', $class_name);
    include __DIR__ . '/' . $class_name . '.php';
});
