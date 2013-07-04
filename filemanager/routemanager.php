<?php

namespace OCA\Marble\FileManager;

class RouteManager extends FileManager {

    public static function read($userId, $timestamp) {
        return parent::read($userId, 'routes/' . $timestamp);
    }

    public static function write($userId, $timestamp, $kml) {
        return parent::write($userId, 'routes/' . $timestamp, $kml);
    }

    public static function delete($userId, $timestamp) {
        return parent::delete($userId, 'routes/' . $timestamp);
    }

}
