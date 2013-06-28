<?php

namespace OCA\Marble\External;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\Http;

// to remove
use \OCA\Marble\Db\RouteMapper;
use \OCA\Marble\Db\Route;

use \OCA\Marble\BusinessLayer\RouteBusinessLayer;


class RouteAPI extends Controller {

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
    public function getAll() {
        $layer = new RouteBusinessLayer($this->api);
        $userId = $this->api->getUserId();

        $routes = $layer->findAll($userId);

        return new JSONResponse($routes);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function create() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');
        $name = $this->params('name');
        $distance = $this->params('distance');
        $duration = $this->params('duration');
        

        $layer->create($userId, $timestamp, $name, $distance, $duration);

        return new JSONResponse(array(), Http::STATUS_CREATED);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function delete() {
        $layer = new RouteBusinessLayer($this->api);
        $layer->delete($this->api->getUserId(), $this->params('timestamp'));

        return new JSONResponse(array(), Http::STATUS_OK);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function rename() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');
        $newName = $this->params('newName');

        $layer->rename($userId, $timestamp, $newName);

        return new JSONResponse(array(), Http::STATUS_NO_CONTENT);
    }

}
