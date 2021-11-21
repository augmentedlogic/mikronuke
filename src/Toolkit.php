<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/
namespace com\augmentedlogic\mikronuke;

class Toolkit
{
    const RANDOM_HEX = 1;
    const RANDOM_BASE64 = 2;
    const RANDOM_ALNUM = 3;

    public static function log($msg, $level = 1, $filename = "mikronuke.log")
    {
        if($level <= MN_LOG_LEVEL) {
            file_put_contents(MN_LOG_DIR."/". $filename, date("Y M j G:i:s", time())." ".$msg."\n", FILE_APPEND);
        }
    }


    public static function twig_get()
    {
        $loader = new \Twig\Loader\FilesystemLoader(MN_TWIG_VIEW_DIR);
        $twig = new \Twig\Environment($loader, ['cache' => MN_TWIG_CACHE_DIR,]);
        return $twig;
    }

    public static function getTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader(MN_TWIG_VIEW_DIR);
        $twig = new \Twig\Environment($loader, ['cache' => MN_TWIG_CACHE_DIR,]);
        return $twig;
    }

    private static function enc_string($length, $encoding) {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes($length);
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes($length);
        } else {
            throw new Exception("no secure random function available");
        }

        if($encoding == 1) {
           return substr(bin2hex($bytes), 0, $length);
        } elseif ($encoding == 2) {
           return substr(base64_encode($bytes), 0, $length);
        } elseif ($encoding == 3) {
           return $bytes;
        }
    }

    public static function genUUIDv4(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
    }

    private static function random_str(
            int $length = 64,
            string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {

            if ($length < 1) {
               throw new \RangeException("Length must be a positive integer");
            }

        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

     /**
      *
      **/
     public static function genRandomString($length, $encoding = Toolkit::RANDOM_BASE64) : string
     {
         $hs = "";
         switch($encoding)
         {
              case Toolkit::RANDOM_HEX:
                       $hs = Toolkit::enc_string($length, $encoding);
              break;

              case Toolkit::RANDOM_BASE64:
                       $hs = Toolkit::enc_string($length, $encoding);
              break;

              case Toolkit::RANDOM_ALNUM:
                        $hs = Toolkit::random_str($length);
              break;

         }

         return $hs;
     }

}


