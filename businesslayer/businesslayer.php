<?php

namespace OCA\Marble\BusinessLayer;

class BusinessLayer {

    protected $mapper;

    public function __construct($mapper) {
        $this->mapper = $mapper;
    }

}
