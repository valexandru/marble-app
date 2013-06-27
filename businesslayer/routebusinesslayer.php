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

    public function create($params) {
        $params['userId'] = $this->api->getUserId();

        // Build the Route
        $route = Route::fromParams($params);

        // Insert it
        $this->mapper->insert($route);
    }

    public function delete($userId, $timestamp) {
        #fixme don't pass $userId. get it from $this->api
        $route = $this->mapper->find($userId, $timestamp);
        $this->mapper->delete($route);
    }

    public function rename($params) {
        $userId = $this->api->getUserId();
        $timestamp = $params['timestamp'];

        $route = $this->mapper->find($userId, $timestamp);
        $route->setName($params['new_name']);

        $this->mapper->update($route);
    }

}
