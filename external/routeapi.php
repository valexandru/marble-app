<?php

namespace OCA\Marble\External;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\Http;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\TextResponse;

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
    public function get() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');

        try {
            $kml = $layer->get($userId, $timestamp);

            return new TextResponse($kml);
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
    public function getPreview() {
        $layer = new RouteBusinessLayer($this->api);

        $userId = $this->api->getUserId();
        $timestamp = $this->params('timestamp');

        try {
            $preview = $layer->getPreview($userId, $timestamp);

            return new TextResponse($preview);
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
    public function getAll() {
        $layer = new RouteBusinessLayer($this->api);
        $userId = $this->api->getUserId();

        $routes = $layer->getAll($userId);

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
    public function create() {
        $layer = new RouteBusinessLayer($this->api);

        try {
            $userId = $this->api->getUserId();
            $timestamp = $this->params('timestamp');
            $name = $this->params('name');
            $distance = $this->params('distance');
            $duration = $this->params('duration');

            $kml_file = $this->getUploadedFile('kml');
            $kml = file_get_contents($kml_file['tmp_name']);

            $preview_file = $this->getUploadedFile('preview');
            $preview = file_get_contents($preview_file['tmp_name']);

            $layer->create($userId, $timestamp, $name, $distance, $duration, $kml, $preview);

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
