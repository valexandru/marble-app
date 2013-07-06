<?php

namespace OCA\Marble\FileManager;

class BookmarksManager extends FileManager {

    public static function readKML($userId) {
        return parent::read($userId, 'bookmarks/kml');
    }

    public static function writeKML($userId, $kml) {
        return parent::write($userId, 'bookmarks/kml', $kml);
    }

    public static function readJSON($userId) {
        return parent::read($userId, 'bookmarks/json');
    }

    public static function writeJSON($userId, $json) {
        return parent::write($userId, 'bookmarks/json', $json);
    }

}
