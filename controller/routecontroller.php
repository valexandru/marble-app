<?php

namespace OCA\Marble\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\Http;
use \OCA\AppFramework\Http\JSONResponse;

use \OCA\Marble\Db\RouteMapper;
use \OCA\Marble\BusinessLayer\RouteBusinessLayer;

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

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     */
    public function rename() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');
        $newName = $this->params('newName');

        try {
            $layer->rename($userId, $timestamp, $newName);

            return new JSONResponse(array(
                'status' => 'success'
            ), Http::STATUS_OK);
        } catch (BusinessLayerException $e) {
            return new JSONResponse(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ), Http::STATUS_BAD_REQUEST);
        }
    }

}
