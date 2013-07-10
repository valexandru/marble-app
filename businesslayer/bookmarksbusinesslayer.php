<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\FileManager\BookmarksManager;
use \OCA\Marble\FileManager\FileManagerException;
use \OCA\Marble\Util\XML2Array;
use \OCA\Marble\Util\Array2XML;

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
            $json = json_encode(XML2Array::createArray($kml));

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
            $kml = Array2XML::createXML('kml', $array['kml'])->saveXML();
            // TODO: add namespaces

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
