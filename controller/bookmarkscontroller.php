<?php

namespace OCA\Marble\Controller;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\Http;

use \OCA\Marble\BusinessLayer\BookmarksBusinessLayer;

class BookmarksController extends Controller {

    public function __construct($api, $request) {
        parent::__construct($api, $request);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
	 * @Ajax
     */
    public function get() {
        $layer = new BookmarksBusinessLayer($this->api);

        $userId = $this->api->getUserId();

        try {
            $json = $layer->getJSON($userId);

            return new JSONResponse(array(
                "status" => "success",
                "data" => json_decode($json)
            ), Http::STATUS_OK);
        } catch (BusinessLayerException $e) {
            return new JSONResponse(array(
                "status" => "error",
                "message" => $e->getMessage()
            ), Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     */
    public function update() {
        $layer = new BookmarksBusinessLayer($this->api);

        $json = $this->params('json');
        $userId = $this->api->getUserId();

        try {
            return new JSONResponse(array(
                "status" => "success",
                "data" => $layer->updateJSON($userId, $json)
            ), Http::STATUS_OK);
        } catch (BusinessLayerException $e) {
            return new JSONResponse(array(
                "status" => "error",
                "message" => $e->getMessage()
            ), Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     */
    public function timestamp() {
        $layer = new BookmarksBusinessLayer($this->api);

        $userId = $this->api->getUserId();

        try {
            return new JSONResponse(array(
                "status" => "success",
                "data" => $layer->timestamp($userId)
            ), Http::STATUS_OK);
        } catch (BusinessLayerException $e) {
            return new JSONResponse(array(
                "status" => "error",
                "message" => $e->getMessage()
            ), Http::STATUS_BAD_REQUEST);
        }
    }

}
