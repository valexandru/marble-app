<?php

namespace OCA\Marble\BusinessLayer;

class BusinessLayer {

    protected $api;
    protected $mapper;

    public function __construct($api, $mapper) {
        $this->api = $api;
        $this->mapper = $mapper;
    }

}
