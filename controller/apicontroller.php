<?php

namespace OCA\Marble\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\Http;

use \OCA\Marble\Db\RouteMapper;
use \OCA\Marble\Db\Route;
use \OCA\Marble\BusinessLayer\RouteBusinessLayer;


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
    public function routesCreate() {
        $layer = new RouteBusinessLayer($this->api);
        $layer->create($this->getParams());

        return new JSONResponse(array(), Http::STATUS_CREATED);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function routesDelete() {
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
    public function routesRename() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');
        $newName = $this->params('newName');

        $layer->rename($userId, $timestamp, $newName);

        return new JSONResponse(array(), Http::STATUS_NO_CONTENT);
    }

}
