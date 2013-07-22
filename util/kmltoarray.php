<?php

namespace OCA\Marble\Util;

class KmlToArray {

    public static function toArray($kml) {
        $r = [];

        try {
            $sxe = new \SimpleXMLElement($kml);
        } catch (\Exception $e) {
            throw new \Exception('Could not convert to array; the provided KML is not valid XML');
        }

        $Document = $sxe->Document;

        foreach ($Document->Folder as $f) {
            $r[] = self::folderToArray($f);
        }

        foreach ($Document->Placemark as $placemark) {
            $r[] = self::placemarkToArray($placemark);
        }

        return $r;
    }

    private static function folderToArray($folder) {
        $r = [];

        $r['label'] = (string) $folder->name;
        $r['visibility'] = (string) $folder->visibility;
        $r['is_folder'] = true;

        foreach ($folder->Folder as $f) {
            $r['children'][] = self::folderToArray($f);
        }

        foreach ($folder->Placemark as $placemark) {
            $r['children'][] = self::placemarkToArray($placemark);
        }

        return $r;
    }

    private static function placemarkToArray($placemark) {
        $r = [];

        $r['label'] = (string) $placemark->name;
        $r['visibility'] = (string) $placemark->visibility;
        $r['description'] = (string) $placemark->description;
        $r['is_folder'] = false;

        $r['point_coordinates'] = (string) $placemark->Point->coordinates;

        $r['lookat_longitude'] = (string) $placemark->LookAt->longitude;
        $r['lookat_latitude'] = (string) $placemark->LookAt->latitude;
        $r['lookat_altitude'] = (string) $placemark->LookAt->altitude;
        $r['lookat_range'] = (string) $placemark->LookAt->range;

        return $r;
    }

}
