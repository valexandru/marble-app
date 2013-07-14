<?php

namespace OCA\Marble\FileManager;

use \OC\Files\View;

class FileManager {

    private static $view;

    public static function read($userId, $path) {
        self::init($userId);

        $contents = self::$view->file_get_contents($userId . '/marble/' . $path);
        if (!$contents)
            throw new FileManagerException('Could not read from file \'' . $path . '\'');

        return $contents;
    }

    public static function write($userId, $path, $contents) {
        self::init($userId);

        if (!$r = self::$view->file_put_contents($userId . '/marble/' . $path, $contents))
            throw new FileManagerException('Could not write to file \'' . $path . '\'');

        return $r;
    }

    public static function delete($userId, $path) {
        self::init($userId);

        if (!$r = self::$view->unlink($userId . '/marble/' . $path))
            throw new FileManagerException('Could not delete file \'' . $path . '\'');

        return $r;
    }

    public static function filemtime($userId, $path) {
        self::init($userId);

        if (!$mtime = self::$view->filemtime($userId . '/marble/' . $path))
            throw new FileManagerException('Couldn\'t get the timestamp for file \'' . $path . '\'');

        return $mtime;
    }

    public static function init($userId) {
        self::$view = new View('');

        if (!self::$view->is_dir($userId . '/marble')) {
            if (!self::$view->mkdir($userId . '/marble') ||
                !self::$view->mkdir($userId . '/marble/routes') ||
                !self::$view->mkdir($userId . '/marble/bookmarks')) {
                throw new FileManagerException('Couldn\'t setup Marble directories');
            }
        }
        return true;
    }

}
