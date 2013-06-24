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

$this->create('marble_routes_get_all', '/routes')->get()->action(
    function($params) {
        App::main('RouteController', 'getAll', $params, new DIContainer());
    }
);
