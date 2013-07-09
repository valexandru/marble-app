<?php

namespace OCA\Marble\External;

use \OCA\AppFramework\Controller\Controller;
use \OCA\AppFramework\Http\Http;
use \OCA\AppFramework\Http\JSONResponse;
use \OCA\AppFramework\Http\TextResponse;

use \OCA\Marble\BusinessLayer\BookmarksBusinessLayer;
use \OCA\Marble\BusinessLayer\BusinessLayerException;

class BookmarksAPI extends Controller {

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
        $layer = new BookmarksBusinessLayer($this->api);

        $userId = $this->api->getUserId();

        try {
            $kml = $layer->getKML($userId);

            return new TextResponse($kml);
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
     * @CSRFExemption
     * @API
     */
    public function update() {
        $layer = new BookmarksBusinessLayer($this->api);

        $bms_file = $this->getUploadedFile('bookmarks');

        $bms = file_get_contents($bms_file['tmp_name']);
        $userId = $this->api->getUserId();

        try {
            return new JSONResponse(array(
                "status" => "success",
                "data" => $layer->updateKML($userId, $bms)
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
     * @CSRFExemption
     * @API
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
