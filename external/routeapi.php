<?php

namespace OCA\Marble\External;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\Http;
use \OCA\AppFramework\Http\JSONResponse;

use \OC\Files\View;

use \OCA\Marble\BusinessLayer\RouteBusinessLayer;
use \OCA\Marble\BusinessLayer\BusinessLayerException;


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

        return new JSONResponse(array(
            'status' => 'success',
            'data' => $routes
        ), Http::STATUS_OK);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function get() {
        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');

        $view = new View('');
        $kml = $view->file_get_contents($userId . '/marble/routes/' . $timestamp);

        return new JSONResponse(array(
            'status' => 'success',
            'data' => $kml
        ), Http::STATUS_OK);
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

        try {
            $userId = $this->api->getUserId();
            $timestamp = $this->params('timestamp');
            $name = $this->params('name');
            $distance = $this->params('distance');
            $duration = $this->params('duration');

            $layer->create($userId, $timestamp, $name, $distance, $duration);

            // Write the kml to file
            $kml = $this->params('kml');

            // TODO find a more elegant solution for this
            // and make sure directory exists
            $view = new View('');
            $view->file_put_contents($userId . '/marble/routes/' . $timestamp, $kml);

            return new JSONResponse(array(
                'status' => 'success'
            ), Http::STATUS_CREATED);
        } catch (BusinessLayerException $e) {
            return new JSONResponse(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ), Http::STATUS_BAD_REQUEST);
        }
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

        try {
            $layer->delete($this->api->getUserId(), $this->params('timestamp'));

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

        return new JSONResponse(array(
            'status' => 'success'
        ), Http::STATUS_OK);
    }

}
