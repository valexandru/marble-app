<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\FileManager\BookmarksManager;
use \OCA\Marble\FileManager\FileManagerException;
use \OCA\Marble\Util\XML2Array;

class BookmarksBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct($api);
    }

    public function getKML($userId) {
        try {
            return BookmarksManager::readKML($userId);
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

    public function updateKML($userId, $kml) {
        try {
            $json = json_encode(XML2Array::createArray($kml));

            BookmarksManager::writeKML($userId, $kml);
            BookmarksManager::writeJSON($userId, $json);
        } catch (\Exception $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

}
