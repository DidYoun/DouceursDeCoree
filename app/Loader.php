<?php

/**
 * Class Loader
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Boot;

class Loader
{
    /**
     * Require helpers classes
     */
    public static function run()
    {
        /** @var array $loadDir */
        $loadDir = [];
        /** @var string $dirname */
        $dirname = __DIR__;
        foreach (scandir($dirname) as $folder) {
            if ($folder === '.' or $folder === '..') {
                continue;
            }
            if (is_dir($dirname . DS . $folder)) {
                /** @var string $path */
                $path = $dirname . DS . $folder;
                $loadDir[$path] = glob($path . DS . '*.php');
            }
        }

        self::_run($loadDir);
    }

    /**
     * Require all the php files
     *
     * @param array $loadDir
     * @return bool
     */
    protected static function _run($loadDir)
    {
        if (!isset($loadDir)) {
            return false;
        }
        foreach ($loadDir as $directory) {
            foreach ($directory as $file) {
                require_once($file);
            }
        }
    }
}