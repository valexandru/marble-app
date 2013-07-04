<?php

namespace OCA\Marble\FileManager;

use \OC\Files\View;

class FileManager {

    public static function read($userId, $path) {
        $view = new View('');

        $contents = $view->file_get_contents($userId . '/' . $path);
        if (!$contents)
            throw FileManagerException('Could not read from file.');

        return $contents;
    }

    public static function write($userId, $path, $contents) {
        $view = new View('');
        if (!$r = $view->file_put_contents($userId . '/' . $path, $contents))
            throw new FileManagerException('Could not write to file.');

        return $r;
    }

    public static function delete($userId, $path) {
        $view = new View('');
        if (!$r = $view->unlink($userId . '/' . $path))
            throw FileManagerException('Could not delete the file.');

        return $r;
    }

}
