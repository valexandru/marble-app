<?php

namespace OCA\Marble\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use \OCA\Marble\Controller\PageController;
use \OCA\Marble\Controller\RouteController;
use \OCA\Marble\Controller\BookmarksController;

use \OCA\Marble\External\RouteAPI;
use \OCA\Marble\External\BookmarksAPI;

class DIContainer extends BaseContainer {

    public function __construct() {
        parent::__construct('marble');

        /**
         * WEB INTERFACE
         */
        $this['PageController'] = $this->share(function($c) {
            return new PageController($c['API'], $c['Request']);
        });


        /**
         * CONTROLLERS
         */
        $this['RouteController'] = $this->share(function($c) {
            return new RouteController($c['API'], $c['Request']);
        });

        $this['BookmarksController'] = $this->share(function($c) {
            return new BookmarksController($c['API'], $c['Request']);
        });


        /**
         * API
         */
        $this['RouteAPI'] = $this->share(function($c) {
            return new RouteAPI($c['API'], $c['Request']);
        });

        $this['BookmarksAPI'] = $this->share(function($c) {
            return new BookmarksAPI($c['API'], $c['Request']);
        });

    }

}
