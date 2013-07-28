<?php

namespace OCA\Marble;

use \OCA\AppFramework\App;

use \OCA\Marble\DependencyInjection\DIContainer;


/**
 * WEB INTERFACE
 */
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
 * BOOKMARKS
 */
$this->create('marble_bookmarks_get', '/bookmarks/json')->get()->action(
    function($params) {
        App::main('BookmarksController', 'get', $params, new DIContainer());
    }
);

$this->create('marble_bookmarks_update', '/bookmarks/update')->post()->action(
    function($params) {
        App::main('BookmarksController', 'update', $params, new DIContainer());
    }
);

$this->create('marble_bookmarks_timestamp', '/bookmarks/timestamp')->get()->action(
    function($params) {
        App::main('BookmarksController', 'timestamp', $params, new DIContainer());
    }
);

/**
 * API
 */
$this->create('marble_api_routes_get', '/api/v1/routes/{timestamp}')->get()->action(
    function($params) {
        App::main('RouteAPI', 'get', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_get_preview', '/api/v1/routes/preview/{timestamp}')->get()->action(
    function($params) {
        App::main('RouteAPI', 'getPreview', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_get_all', '/api/v1/routes')->get()->action(
    function($params) {
        App::main('RouteAPI', 'getAll', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_create', '/api/v1/routes/create')->post()->action(
    function($params) {
        App::main('RouteAPI', 'create', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_delete', '/api/v1/routes/delete/{timestamp}')->delete()->action(
    function($params) {
        App::main('RouteAPI', 'delete', $params, new DIContainer());
    }
);

$this->create('marble_api_routes_rename', '/api/v1/routes/rename')->put()->action(
    function($params) {
        App::main('RouteAPI', 'rename', $params, new DIContainer());
    }
);



$this->create('marble_api_bookmarks_get', '/api/v1/bookmarks/kml')->get()->action(
    function($params) {
        App::main('BookmarksAPI', 'get', $params, new DIContainer());
    }
);

$this->create('marble_api_bookmarks_update', '/api/v1/bookmarks/update')->post()->action(
    function($params) {
        App::main('BookmarksAPI', 'update', $params, new DIContainer());
    }
);

$this->create('marble_api_bookmarks_timestamp', '/api/v1/bookmarks/timestamp')->get()->action(
    function($params) {
        App::main('BookmarksAPI', 'timestamp', $params, new DIContainer());
    }
);
