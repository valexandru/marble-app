<?php

namespace OCA\Marble\DependencyInjection;

use \OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use \OCA\Marble\Controller\PageController;

class DIContainer extends BaseContainer {

    public function __construct(){
        parent::__construct('marble');

        // use this to specify the template directory
        // $this['TwigTemplateDirectory'] = __DIR__ . '/../templates';

        /**
         * CONTROLLERS
         */
        $this['PageController'] = function($c){
            return new PageController($c['API'], $c['Request']);
        };
    }

}
