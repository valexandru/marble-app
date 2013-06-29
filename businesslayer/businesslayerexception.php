<?php

namespace OCA\Marble\BusinessLayer;

class BusinessLayerException extends \Exception {

    public function __construct($msg) {
        parent::__construct($msg);
    }

}
