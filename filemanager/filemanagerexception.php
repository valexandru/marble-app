<?php

namespace OCA\Marble\FileManager;

class FileManagerException extends \Exception {

    public function __construct($msg) {
        parent::__construct($msg);
    }

}
