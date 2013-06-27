<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\Db\RouteMapper;

class RouteBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct(new RouteMapper($api));
    }

    public function findAll($userId) {
        $routes = $this->mapper->findAll($userId);

        // Take out the userId fields
        $finalRoutes = array();
        foreach ($routes as $route) {
            array_push($finalRoutes, $route->toAPI());
        }

        return $finalRoutes;
    }

}
