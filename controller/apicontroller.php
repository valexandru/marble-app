<?php

namespace OCA\Marble\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\Marble\Db\RouteMapper;
use \OCA\Marble\Db\Route;

use \OCA\AppFramework\Http\TextResponse;

class APIController extends Controller {

    public function __construct($api, $request) {
        parent::__construct($api, $request);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function routesGetAll() {
        $mapper = new RouteMapper($this->api);
        $userId = $this->api->getUserId();

        $routesList = $mapper->findAll($userId);
        
        return new JSONResponse($routesList);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function routesCreate() {
        $mapper = new RouteMapper($this->api);

        // Fields
        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');
        $name = $this->params('name');
        $distance = $this->params('distance');
        $duration = $this->params('duration');

        //try {
            $mapper->save(new Route(array(
            'user_id' => $userId,
            'timestamp' => $timestamp,
            'name' => $name,
            'distance' => $distance,
            'duration' => $duration
        )));
        //} catch (Exception $e) {
            return new JSONResponse(array(), 400);
        //}
        //return new JSONResponse(array());
    }

}
