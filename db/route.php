<?php

namespace OCA\Marble\Db;

use \OCA\AppFramework\Db\Entity;

class Route extends Entity {

    public $userId;
    public $timestamp;
    public $name;
    public $duration;
    public $distance;

    public function __construct() {
        $this->addType('duration', 'int');
        $this->addType('distance', 'int');
    }

    public function toAPI() {
        return array(
            'timestamp' => $this->getTimestamp(),
            'name' => $this->getName(),
            'duration' => $this->getDuration(),
            'distance' => $this->getDistance()
        );
    }
}
