<?php

/**
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 */

namespace com\augmentedlogic\mikronuke;

class Toolkit
{
    public const RANDOM_HEX = 1;
    public const RANDOM_BASE64 = 2;
    public const RANDOM_ALNUM = 3;
    public const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    public const NANESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    public const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

    private static function enc_string(int $length, int $encoding): string
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
        } else {
            throw new Exception('no secure random function available');
        }

        if ($encoding == 1) {
            return substr(bin2hex($bytes), 0, $length);
        } elseif ($encoding == 2) {
            return substr(base64_encode($bytes), 0, $length);
        } elseif ($encoding == 3) {
            return $bytes;
        }
    }

    private static function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length < 1) {
            throw new \RangeException('Length must be a positive integer');
        }

        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    /**
     * public Toolkit methods start here
     */
    #[\Deprecated(message: 'use the TwigWrap class instead', since: '0.2.1')]
    public static function twig_get()
    {
        $loader = new \Twig\Loader\FilesystemLoader(MN_TWIG_VIEW_DIR);
        $twig = new \Twig\Environment($loader, [
            'cache' => MN_TWIG_CACHE_DIR,
        ]);
        return $twig;
    }

    #[\Deprecated(message: 'use the Log class instead', since: '0.2.1')]
    public static function log($msg, $level = 1, $filename = 'app.log')
    {
        if ($level <= MN_LOG_LEVEL) {
            file_put_contents(MN_LOG_DIR . '/' . $filename, date('Y M j G:i:s', time()) . ' ' . $msg . "\n", FILE_APPEND);
        }
    }

    // Will be replaced by a dedicated class in future versions
    #[\Deprecated(message: 'use the TwigWrap class instead', since: '0.2.1')]
    public static function getTwig()
    {
        $twig = null;
        if (class_exists('Twig\Loader\FilesystemLoader')) {
            $loader = new \Twig\Loader\FilesystemLoader(MN_TWIG_VIEW_DIR);
            $twig = new \Twig\Environment($loader, [
                'cache' => MN_TWIG_CACHE_DIR,
            ]);
        }
        return $twig;
    }

    public static function slugify(string $s, bool $ext = false): string
    {
        $s = preg_replace('/[^\pL\d]+/u', '-', trim($s));
        $s = trim($s, '-');
        $s = iconv('utf-8', 'us-ascii//TRANSLIT', $s);
        $s = strtolower($s);
        $s = preg_replace('/[^-\w]+/', '', $s);
        if ($ext == true) {
            $s = $s . '-' . bin2hex(random_bytes(3));
        }
        return $s;
    }

    public static function imageToBase64(string $path): ?string
    {
        $base64 = null;
        if (file_exists($path)) {
            try {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $path = 'myfolder/myimage.png';
            } catch (Exception $e) {
                return null;
            }
        }
        return $base64;
    }

    public static function genToken(int $length = 16, int $enc = 0): string
    {
        if ($enc == 1) {
            return base64_encode(random_bytes($length));
        }
        return bin2hex(random_bytes($length));
    }

    public static function genUUIDv4(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFF) | 0x4000,
            mt_rand(0, 0x3FFF) | 0x8000,
            mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF));
    }

    public static function genUUIDv5(string $namesp, string $name): string
    {
        $nhex = str_replace(array('-', '{', '}'), '', $namesp);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        $hash = sha1($nstr . $name);

        return sprintf('%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0xFFF) | 0x5000,
            (hexdec(substr($hash, 16, 4)) & 0x3FFF) | 0x8000,
            substr($hash, 20, 12));
    }

    public static function genRandomString(int $length, int $encoding = Toolkit::RANDOM_BASE64): string
    {
        $hs = '';
        switch ($encoding) {
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
