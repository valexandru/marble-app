<?php

namespace OCA\Marble\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use \OCA\Marble\Controller\PageController;
use \OCA\Marble\Controller\RouteController;

use \OCA\Marble\External\RouteAPI;

class DIContainer extends BaseContainer {

    public function __construct() {
        parent::__construct('marble');

        // use this to specify the template directory
        // $this['TwigTemplateDirectory'] = __DIR__ . '/../templates';


        /**
         * CONTROLLERS
         */
        $this['PageController'] = $this->share(function($c) {
            return new PageController($c['API'], $c['Request']);
        });

        $this['RouteController'] = $this->share(function($c) {
            return new RouteController($c['API'], $c['Request']);
        });


        /**
         * API
         */
        $this['RouteAPI'] = $this->share(function($c) {
            return new RouteAPI($c['API'], $c['Request']);
        });


    }

}
