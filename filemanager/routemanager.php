<?php

namespace OCA\Marble\FileManager;

class RouteManager extends FileManager {

    public static function readKML($userId, $timestamp) {
        return parent::read($userId, 'routes/kml/' . $timestamp);
    }

    public static function writeKML($userId, $timestamp, $kml) {
        return parent::write($userId, 'routes/kml/' . $timestamp, $kml);
    }

    public static function deleteKML($userId, $timestamp) {
        return parent::delete($userId, 'routes/kml/' . $timestamp);
    }

    public static function readPreview($userId, $timestamp) {
        return parent::read($userId, 'routes/preview/' . $timestamp);
    }

    public static function writePreview($userId, $timestamp, $preview) {
        return parent::write($userId, 'routes/preview/' . $timestamp, $preview);
    }

    public static function deletePreview($userId, $timestamp) {
        return parent::delete($userId, 'routes/preview/' . $timestamp);
    }

}
