<?php

namespace com\augmentedlogic\mikronuke;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;

class Setup
{
    private array $argv = array();
    private ?string $install_dir = null;

    private function getArgument(string $arg, mixed $default_value = null): mixed
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

    public function runSetup($argv)
    {
        $this->argv = $argv;

        $this->install_dir = $this->getArgument('-d', null);
        $plain = false;
        $mn_loader = __DIR__ . '/mn_loader.php';

        if (isset($argv[1])) {
            if ($argv[1] == 'plain') {
                $plain = true;
            }
        }

        $target_dir = $this->install_dir;
        if ($target_dir == null) {
            $target_dir = getcwd();
        }

        $dirs = array('public', 'app/src', 'app/view', 'log');
        foreach ($dirs as $dir) {
            if (!file_exists($target_dir . '/' . $dir)) {
                mkdir($target_dir . '/' . $dir, 0777, true);
            }
        }
        if ($plain) {
            $index_file = file_get_contents(__DIR__ . '/skel/skel_idx_plain.tpl');
            $index_file = str_replace('%mn_loader%', $mn_loader, $index_file);
            file_put_contents($target_dir . '/public/index.php', $index_file);
        } else {
            $index_file = file_get_contents(__DIR__ . '/skel/skel_idx.tpl');
            $index_file = str_replace('%target%', $target_dir, $index_file);
            file_put_contents($target_dir . '/public/index.php', $index_file);
        }
        copy(__DIR__ . '/skel/skel_hdl.tpl', $target_dir . '/app/src/DefaultHandler.php');
    }

    public static function run(Event $event)
    {
        $a = array();
        self::runSetup($a);
    }
}
