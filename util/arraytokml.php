<?php

namespace OCA\Marble\Util;

class ArrayToKml {

    public static function toKml($array) {
        $baseKML = '<?xml version="1.0" encoding="UTF-8"?>' .
            '<kml xmlns="http://earth.google.com/kml/2.2" ' .
            'xmlns:gx="http://www.google.com/kml/ext/2.2">' .
            '<Document></Document></kml>';
        $sxe = new \SimpleXMLElement($baseKML);
        $document = $sxe->Document;

        foreach ($array as $item) {
            if ($item['is_folder']) {
                $newFolder = $document->addChild('Folder');
                self::arrayToFolder($item, $newFolder);
            } else {
                $newPlacemark = $document->addChild('Placemark');
                self::arrayToPlacemark($item, $newPlacemark);
            }
        }

        /* Format the output */
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($sxe->asXML());
        return $dom->saveXML();
    }

    private static function arrayToFolder($array, &$folder) {
        $folder->addChild('name', $array['label']);
        $folder->addChild('visibility', $array['visibility']);

        foreach ($array['children'] as $child) {
            if ($child['is_folder']) {
                $newFolder = $folder->addChild('Folder');
                self::arrayToFolder($child, $newFolder);
            } else {
                $newPlacemark = $folder->addChild('Placemark');
                self::arrayToPlacemark($child, $newPlacemark);
            }
        }
    }

    private static function arrayToPlacemark($array, &$placemark) {
        $placemark->addChild('name', $array['label']);
        $placemark->addChild('visibility', $array['visibility']);
        $placemark->addChild('description', $array['description']);

        $placemark->addChild('ExtendedData');
        $placemark->ExtendedData->addChild('Data');
        $placemark->ExtendedData->Data->addAttribute('name', 'isBookmark');
        $placemark->ExtendedData->Data->addChild('value', 'true');

        $placemark->addChild('Point');
        $placemark->Point->addChild('coordinates', $array['point_coordinates']);

        $placemark->addChild('LookAt');
        $placemark->LookAt->addChild('longitude', $array['lookat_longitude']);
        $placemark->LookAt->addChild('latitude', $array['lookat_latitude']);
        $placemark->LookAt->addChild('altitude', $array['lookat_altitude']);
        $placemark->LookAt->addChild('range', $array['lookat_range']);
    }

}
