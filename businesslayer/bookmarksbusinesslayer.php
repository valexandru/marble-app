<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\FileManager\BookmarksManager;
use \OCA\Marble\FileManager\FileManagerException;

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
            return BookmarksManager::writeKML($userId, $kml);
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

}
