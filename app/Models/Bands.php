<?php

/**
 * Class Bands
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Models;

class Bands extends AbstractDb
{
    /**
     * Bands constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        $sth = $this->connection->query("SELECT * FROM users");
        $result = $sth->fetchAll();

        return $result;
    }

    /**
     *  Create Band
     *          Create a band 
     *  @param array $params
     *  @return boolean execute()
     */
    public function createBand($params){
        $sth = $this->connection->query("INSERT INTO band (name, length, date_creation, agency) VALUES(:name, :length, :date_creation, :agency)");
        $sth->bindParam(':name', $params['name']);
        $sth->bindParam(':length', $params['length']);
        $sth->bindParam(':date_creation', $params['date_creation']);
        $sth->bindParam(':agency', $params['agency']);
        
        return (bool) $sth->execute();
    }

    /**
     *  Remove Band 
     *          Remove a band 
     *  @param int $bandID
     *  @return bool execute()
     */
    public function removeBand($bandID){
        $sth = $this->connection->query("DELETE FROM band WHERE id = :bandID");
        $sth->bindParam(':bandID', $bandID, PDO::PARAM_INT);

        return (bool) $sth->execute();
    }

    /**
     *  Update Band
     *          Update the bnds based on a value 
     *  @param mixed var $value
     *  @param string $paramName
     *  @return bool execute()
     */
    public function updateBand($value, $paramName){
        $sth = $this->connection->query("UPDATE band SET ".$paramName." = :value");
        $sth->bindParam(':value', $value);

        return (bool) $sth->execute();
    }

    /**
     *  Get All Band
     *          Get every band of the databases
     *  @return PDOObject
     */
    public function getAllBands(){
        $sth = $this->connection->query("SELECT * FROM band");
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *  Get Band
     *          Get a band based on it's name
     *  @param string $bandName
     *  @return PDOObject
     */
    public function getBand($bandName){
        $sth = $this->connection->query("SELECT * FROM band WHERE name = :bandName");
        $sth->bindParam(':bandName', $bandName, PDO::PARAM_STR);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}