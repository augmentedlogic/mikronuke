<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class ReceivedFile
{

     private $size = 0;
     private $tmp_name = NULL;
     private $filetype = NULL;
     private $name = NULL;

     public function __construct($file_props)
     {
         $this->tmp_name = $file_props['tmp_name'];
         $this->size = $file_props['size'];
         $this->name = $file_props['name'];
         $this->filetype = $file_props['type'];
     }

     public function getTmpName()
     {
         return $this->tmp_name;
     }

     public function getSize()
     {
         return $this->size;
     }

     public function getType()
     {
         return $this->filetype;
     }

     public function saveTo($targetdir, $name = NULL)
     {
         if(!$name) {
             $name = $this->name;
         }
         move_uploaded_file($this->tmp_name, $targetdir."/".$name);
     }
}
