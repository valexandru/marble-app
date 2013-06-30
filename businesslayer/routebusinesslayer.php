<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\Db\Route;
use \OCA\Marble\Db\RouteMapper;

use \OC\Files\View;

use \OCA\AppFramework\Db\DoesNotExistException;
use \OCA\AppFramework\Db\MultipleObjectsReturnedException;

class RouteBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct($api, new RouteMapper($api));
    }

    public function get($userId, $timestamp) {
        return $this->readKml($userId, $timestamp);
    }

    public function getAll($userId) {
        $routes = $this->mapper->getAll($userId);

        // Keep only required fields, using toAPI()
        $finalRoutes = array();
        foreach ($routes as $route) {
            array_push($finalRoutes, $route->toAPI());
        }

        return $finalRoutes;
    }

    public function create($userId, $timestamp, $name, $distance, $duration, $kml) {
        $route = new Route();
        $route->setUserId($userId);
        $route->setTimestamp($timestamp);
        $route->setName($name);
        $route->setDistance($distance);
        $route->setDuration($duration);

        try {
            $this->mapper->insert($route);
            $this->writeKml($userId, $timestamp, $kml);
        } catch (BusinessLayerException $e) {
            // TOFIX this ugliness
            $this->mapper->delete($route);
            throw $e;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new BusinessLayerException('Duplicate route; ' .
                    'a route with same timestamp already exists.');
            }
            throw new BusinessLayerException('Could not add route; unknown database error.');
        }
    }

    public function delete($userId, $timestamp) {
        try {
            $route = $this->mapper->get($userId, $timestamp);
            $this->mapper->delete($route);
            $this->deleteKml($userId, $timestamp);
        } catch (DoesNotExistException $e) {
            throw new BusinessLayerException('No matching route found; nothing deleted.');
        } catch (MultipleObjectsReturnedException $e) {
            throw new BusinessLayerException('Multiple matching routes; nothing deleted.');
        }
    }

    public function rename($userId, $timestamp, $newName) {
        try {
            $route = $this->mapper->get($userId, $timestamp);
            $route->setName($newName);
            $this->mapper->update($route);
        } catch (DoesNotExistException $e) {
            throw new BusinessLayerException('No matching route found; nothing renamed.');
        } catch (MultipleObjectsReturnedException $e) {
            throw new BusinessLayerException('Multiple matching routes; nothing renamed.');
        }
    }

    /**
     * PRIVATE
     */
    private function writeKml($userId, $timestamp, $kml) {
        $view = new View('');
        if (!$view->file_put_contents($userId . '/marble/routes/' . $timestamp, $kml))
            throw new BusinessLayerException('Could not write the KML contents to file.');
    }

    private function readKml($userId, $timestamp) {
        $view = new View('');

        $kml = $view->file_get_contents($userId . '/marble/routes/' . $timestamp);
        if (!$kml)
            throw BusinessLayerException('Could not read KML contents from file.');

        return $kml;
    }

    private function deleteKml($userId, $timestamp) {
        $view = new View('');
        if (!$view->unlink($userId . '/marble/routes/' . $timestamp))
            throw BusinessLayerException('Could not delete the KML file.');
    }

}
