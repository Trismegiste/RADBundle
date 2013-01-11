<?php

require_once __DIR__ . '/../vendor/PHP-Parser/lib/bootstrap.php';

spl_autoload_register(function ($class) {
            if (preg_match('#^Trismegiste\\\\RADBundle\\\\(.+)$#', $class, $ret)) {
                $relPath = str_replace('\\', DIRECTORY_SEPARATOR, $ret[1]);
                require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $relPath . '.php';
            }
        });