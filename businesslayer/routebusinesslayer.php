<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\Db\Route;
use \OCA\Marble\Db\RouteMapper;

class RouteBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct($api, new RouteMapper($api));
    }

    public function findAll($userId) {
        $routes = $this->mapper->findAll($userId);

        // Keep only required fields, using toAPI()
        $finalRoutes = array();
        foreach ($routes as $route) {
            array_push($finalRoutes, $route->toAPI());
        }

        return $finalRoutes;
    }

    public function create($userId, $timestamp, $name, $distance, $duration) {
        // Build the Route
        $route = new Route();
        $route->setUserId($userId);
        $route->setTimestamp($timestamp);
        $route->setName($timestamp);
        $route->setDistance($distance);
        $route->setDuration($duration);

        // Insert it
        $this->mapper->insert($route);
    }

    public function delete($userId, $timestamp) {
        $route = $this->mapper->find($userId, $timestamp);
        $this->mapper->delete($route);
    }

    public function rename($userId, $timestamp, $newName) {
        $route = $this->mapper->find($userId, $timestamp);
        $route->setName($newName);

        $this->mapper->update($route);
    }

}
