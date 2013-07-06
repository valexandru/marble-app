<?php

namespace OCA\Marble\BusinessLayer;

class BusinessLayer {

    protected $api;
    protected $mapper;

    public function __construct($api, $mapper=null) {
        $this->api = $api;
        $this->mapper = $mapper;
    }

}
