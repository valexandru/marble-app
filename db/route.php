<?php

namespace OCA\Marble\Db;

use \OCA\AppFramework\Db\Entity;
use \OCA\Marble\Db\IAPI;

class Route extends Entity implements IAPI {

    public $userId;
    public $timestamp;
    public $name;
    public $duration;
    public $distance;

    public function __construct() {
        $this->addType('duration', 'int');
        $this->addType('distance', 'int');
    }

    public static function fromParams(array $params) {
        $instance = new self();
        try {
            $instance->setUserId($params['userId']);
            $instance->setTimestamp($params['timestamp']);
            $instance->setName($params['name']);
            $instance->setDuration($params['duration']);
            $instance->setDistance($params['distance']);
        } catch (\Exception $e) {
            throw \Exception('Bad ROUTE parameters');
        }
        return $instance;
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
