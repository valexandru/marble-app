<?php

namespace OCA\Marble\Db;

use OCA\AppFramework\Db\Mapper;
use OCA\AppFramework\Db\DoesNotExistException;

class RouteMapper extends Mapper {

    private $tableName;

    /**
     * @param API $api Instance of the API abstraction layer
     */
    public function __construct($api) {
        parent::__construct($api, 'marble_routes');
        $this->tableName = '*PREFIX*marble_routes';
    }

    /**
     * Finds a route by userId and timestamp
     * @throws DoesNotExistException if the route does not exist
     * @throws MultipleObjectsReturnedException if more than one route exists
     * @return Route the route
     */
    public function find($userId, $timestamp) {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE user_id = ?' .
            ' AND timestamp = ?';
        $params = array($userId, $timestamp);
        $result = $this->execute($sql, $params);

        $row = $result->fetchRow();
        if (! $row) {
            throw new DoesNotExistException('No matching route found!');
        }

        // Check if a second row exists. If yes, raise exception.
        $row2 = $result->fetchRow();
        if ($row2) {
            throw new MultipleObjectsReturnedException('More than one route!');
        }

        return new Route($row);
    }

    /**
     * Returns an array containing all routes owned by user_id
     * @return array the array of routes
     */
    public function findAll($userId) {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE user_id = ?';
        $params = array($userId);
        $result = $this->execute($sql, $params);

        $routes = array();
        while ($row = $result->fetchRow()) {
            //array_push($routes, new Route($row));
            array_push($routes, $row);
        }

        return $routes;
    }

    /**
     * Saves a route in the database
     */
    public function save($route) {
        $sql = 'INSERT INTO ' . $this->tableName .
            ' (user_id, timestamp, name, distance, duration) ' .
            'VALUES(?, ?, ?, ?, ?)';
        $params = array(
            $route->getUserId(),
            $route->getTimestamp(),
            $route->getName(),
            $route->getDistance(),
            $route->getDuration()
        );

        return $this->execute($sql, $params);
    }

}
