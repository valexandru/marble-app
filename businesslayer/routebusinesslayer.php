<?php

namespace OCA\Marble\BusinessLayer;

use \OCA\Marble\Db\Route;
use \OCA\Marble\Db\RouteMapper;
use \OCA\Marble\FileManager\RouteManager;
use \OCA\Marble\FileManager\FileManagerException;

use \OCA\AppFramework\Db\DoesNotExistException;
use \OCA\AppFramework\Db\MultipleObjectsReturnedException;

class RouteBusinessLayer extends BusinessLayer {

    public function __construct($api) {
        parent::__construct($api, new RouteMapper($api));
    }

    public function get($userId, $timestamp) {
        try {
            return RouteManager::read($userId, $timestamp);
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
        }
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
            RouteManager::write($userId, $timestamp, $kml);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new BusinessLayerException('Duplicate route; ' .
                    'a route with same timestamp already exists.');
            }
            throw new BusinessLayerException('Could not add route; unknown database error.');
        } catch (FileManagerException $e) {
            $this->mapper->delete($route);
            throw new BusinessLayerException($e->getMessage());
        }
    }

    public function delete($userId, $timestamp) {
        try {
            $route = $this->mapper->get($userId, $timestamp);
            $this->mapper->delete($route);
            RouteManager::delete($userId, $timestamp);
        } catch (DoesNotExistException $e) {
            throw new BusinessLayerException('No matching route found; nothing deleted.');
        } catch (MultipleObjectsReturnedException $e) {
            throw new BusinessLayerException('Multiple matching routes; nothing deleted.');
        } catch (FileManagerException $e) {
            throw new BusinessLayerException($e->getMessage());
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

}
