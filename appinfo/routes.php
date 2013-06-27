<?php

namespace OCA\Marble;

use \OCA\AppFramework\App;

use \OCA\Marble\DependencyInjection\DIContainer;


$this->create('marble_index', '/')->get()->action(
    function($params) {
        // call the index method on the class PageController
        App::main('PageController', 'index', $params, new DIContainer());
    }
);

/**
 * ROUTES
 */
$this->create('marble_routes_get_all', '/routes')->get()->action(
    function($params) {
        App::main('RouteController', 'getAll', $params, new DIContainer());
    }
);

// TODO the rest. Doing the API for now. See below.

/**
 * API
 */

$this->create('marble_api_routes_get_all', '/api/v1/routes')->get()->action(
    function($params) {
        App::main('APIController', 'routesGetAll', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_create', '/api/v1/routes/create')->post()->action(
    function($params) {
        App::main('APIController', 'routesCreate', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_delete', '/api/v1/routes/delete/{timestamp}')->delete()->action(
    function($params) {
        App::main('APIController', 'routesDelete', $params, new DIContainer());
    }
);
