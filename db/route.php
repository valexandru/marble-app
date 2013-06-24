<?php

namespace OCA\Marble\Db;

class Route {

    //private $id;
    private $userId;
    private $timestamp;
    //private $kml;
    private $name;
    private $duration;
    private $distance;

    public function __construct($row=null) {
        if ($row) {
            $this->fromRow($row);
        }
    }

    public function fromRow($row) {
        //$this->id        = $row['id'];
        $this->userId    = $row['user_id'];
        $this->timestamp = $row['timestamp'];
        //$this->kml       = $row['kml'];
        $this->name      = $row['name'];
        $this->duration  = $row['duration'];
        $this->distance  = $row['distance'];
    }

    public function toArray() {
        return array(
            'user_id'   => $this->userId,
            'timestamp' => $this->timestamp,
            'name'      => $this->name,
            'duration'  => $this->duration,
            'distance'  => $this->distance
        );
    }

    /**
     * GETTERS
     */

/*
    public function getId() {
        return $this->id;
    }
*/

    public function getUserId() {
        return $this->userId;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

/*
    public function getKml() {
        return $this->kml;
    }
*/

    public function getName() {
        return $this->name;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function getDistance() {
        return $this->distance;
    }

    /**
     * SETTERS
     */
/*
    public function setId($id) {
        $this->id = $id;
    }
*/

    public function setUserId($userId) {
        $this->userId = $userId;        
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

/*
    public function setKml($kml) {
        $this->kml = $kml;
    }
*/

    public function setName($name) {
        $this->name = $name;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function setDistance($distance) {
        $this->distance = $distance;
    }

}
