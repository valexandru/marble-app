<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\Db\Route;
use \OCA\Marble\Db\RouteMapper;

use \OCA\AppFramework\Db\DoesNotExistException;

class RouteBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct($api, new RouteMapper($api));
    }

    // TORENAME to getAll
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
        $route->setName($name);
        $route->setDistance($distance);
        $route->setDuration($duration);

        // Insert it
        try {
            $this->mapper->insert($route);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new BusinessLayerException('Duplicate route; ' .
                    'a route with same timestamp already exists.');
            }

            throw new BusinessLayerException('Could not add route; unknown error.');
        }
    }

    public function delete($userId, $timestamp) {
        try {
            $route = $this->mapper->find($userId, $timestamp);
            $this->mapper->delete($route);
        } catch (DoesNotExistException $e) {
            throw new BusinessLayerException('No matching route found; nothing deleted.');
        }
    }

    public function rename($userId, $timestamp, $newName) {
        $route = $this->mapper->find($userId, $timestamp);
        $route->setName($newName);

        $this->mapper->update($route);
    }

}
