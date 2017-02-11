<?php

/**
 * Class AbstractDb
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Models;

use PDO;

class AbstractDb
{
    /** @var null $connection */
    protected $connection = null;

    /**
     * AbstractDb constructor.
     */
    public function __construct()
    {
        $this->connection = $this->_getPdoInstance();
    }

    /**
     * Load data in database
     *
     * @param string $table
     * @param array|null $attributes
     * @param string|null $column
     * @return bool|array
     */
    public function load($table, array $attributes = null, $column = null)
    {
        try {
            /** @var string $select */
            $select = "*";
            if (isset($column)) {
                $select = $column;
            }
            /** @var string $sql */
            $sql = "SELECT " . $select . " FROM " . $table;
            if (isset($attributes)) {
                foreach ($attributes as $key => $attribute) {
                    if (is_int($key)) {
                        $key = $table . '_id';
                    }
                    /**
                     * Create dynamic where statement and add them to sql query
                     */
                    $whereStatement = " WHERE " . $key . " LIKE " . "'" . (string)$attribute . "'";
                    $sql .= $whereStatement;
                }
            }
            $query = $this->connection->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        } catch (\PDOException $e) {
            echo "Error in SQL request";
        }

        return false;
    }

    /**
     * Get instance of PDO connection
     *
     * @return PDO
     */
    public function getConnection()
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        $this->connection = $this->_getPdoInstance();
    }

    /**
     * Get PDO instance
     *
     * @return PDO
     */
    protected function _getPdoInstance()
    {
        try {
            /** @var array $config */
            $config = require(__DIR__ . DS . '..' . DS . '..' . DS . 'config' . DS . 'database.php');
            $instance = new PDO('mysql:host='. $config['db_host'] .';port='. $config['db_port'] .';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }

        return $instance;
    }
}