<?php

namespace com\augmentedlogic\mikronuke;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Setup
{

     public static function runSetup($argv)
     {
                    $plain = false;
                    $mn_loader = __DIR__."/mn_loader.php";

                    if(isset($argv[1])) {
                       if($argv[1] == "plain") {
                          $plain = true;
                       }
                    }

                    $target_dir = getcwd();

                    $dirs = array("public", "app/src", "app/view", "log");
                    foreach($dirs as $dir) {
                        if (!file_exists($target_dir."/".$dir)) {
                            mkdir($target_dir."/".$dir, 0777, true);
                        }
                    }
                    if($plain) {
                        $index_file = file_get_contents(__DIR__."/skel/skel_idx_plain.tpl");
                        $index_file = str_replace("%mn_loader%", $mn_loader, $index_file);
                        file_put_contents($target_dir."/public/index.php", $index_file);
                    } else {
                        $index_file = file_get_contents(__DIR__."/skel/skel_idx.tpl");
                        $index_file = str_replace("%target%", $target_dir, $index_file);
                        file_put_contents($target_dir."/public/index.php", $index_file);
                    }
                    copy(__DIR__."/skel/skel_hdl.tpl",  $target_dir."/app/src/DefaultHandler.php");
      }

      public static function run(Event $event)
      {
                $a = array();
                self::runSetup($a);
      }


}
