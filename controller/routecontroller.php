<?php

namespace OCA\Marble\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\Marble\Db\RouteMapper;

class RouteController extends Controller {

    public function __construct($api, $request) {
        parent::__construct($api, $request);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
	 * @Ajax
     */
    public function getAll() {
        $mapper = new RouteMapper($this->api);
        $userId = $this->api->getUserId();

        $routesList = $mapper->findAll($userId);
        
        return $this->renderJSON($routesList);
    }
}
