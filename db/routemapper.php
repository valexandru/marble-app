<?php

namespace OCA\Marble\Db;

use OCA\AppFramework\Db\Mapper;

class RouteMapper extends Mapper {

    /**
     * @param API $api Instance of the API abstraction layer
     */
    public function __construct($api) {
        parent::__construct($api, 'marble_routes');
    }

    /**
     * Finds a Route by userId and timestamp
     * @throws DoesNotExistException if the route does not exist
     * @throws MultipleObjectsReturnedException if more than one route exists
     * @return Route the route
     */
    public function find($userId, $timestamp) {
        $sql = 'SELECT * FROM `' . $this->getTableName() .
            '` WHERE `user_id` = ? AND `timestamp` = ?';
        $params = array($userId, $timestamp);
        return $this->findEntity($sql, $params);
    }

    /**
     * Returns an array containing all Routes owned by user_id
     * @return array the array of routes
     */
    public function findAll($userId) {
        $sql = 'SELECT * FROM `' . $this->getTableName() .
            '` WHERE `user_id` = ?';
        $params = array($userId);
        return $this->findEntities($sql, $params);
    }

}
