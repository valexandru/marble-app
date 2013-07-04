<?php

namespace OCA\Marble\FileManager;

use \OC\Files\View;

class FileManager {

    public static function read($userId, $path) {
        $view = new View('');

        $contents = $view->file_get_contents($userId . '/marble/' . $path);
        if (!$contents)
            throw new FileManagerException('Could not read from file \'' . $path . '\'');

        return $contents;
    }

    public static function write($userId, $path, $contents) {
        $view = new View('');
        if (!$r = $view->file_put_contents($userId . '/marble/' . $path, $contents))
            throw new FileManagerException('Could not write to file \'' . $path . '\'');

        return $r;
    }

    public static function delete($userId, $path) {
        $view = new View('');
        if (!$r = $view->unlink($userId . '/marble/' . $path))
            throw new FileManagerException('Could not delete file \'' . $path . '\'');

        return $r;
    }

}
