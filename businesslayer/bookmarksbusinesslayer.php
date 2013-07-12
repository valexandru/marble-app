<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\FileManager\BookmarksManager;
use \OCA\Marble\FileManager\FileManagerException;
use \OCA\Marble\Util\KmlToArray;
use \OCA\Marble\Util\ArrayToKml;

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

    public function getJSON($userId) {
        try {
            return BookmarksManager::readJSON($userId);
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

    public function updateKML($userId, $kml) {
        try {
            $array = KmlToArray::toArray($kml);
            $json = json_encode($array);

            BookmarksManager::writeKML($userId, $kml);
            BookmarksManager::writeJSON($userId, $json);

            return BookmarksManager::timestamp($userId);
        } catch (\Exception $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

    public function updateJSON($userId, $json) {
        try {
            $array = json_decode($json, true);
            $kml = ArrayToKml::toKml($array);

            BookmarksManager::writeKML($userId, $kml);
            BookmarksManager::writeJSON($userId, $json);

            return BookmarksManager::timestamp($userId);
        } catch (\Exception $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

    public function timestamp($userId) {
        try {
            return BookmarksManager::timestamp($userId);
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
        }
    }

}
