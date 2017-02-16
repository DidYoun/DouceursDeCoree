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

use PDO;

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
    public function createBand($params, $urlPath){
        $name = $params->name;
        $date = $params->date;
        $agency = $params->agency;
        $description = $params->description;
        $cover = $urlPath;

        // If a group already has the same name
        if($this->isAlreadyPresent($name))
            return array(
                'error' => 'A group already has the same name'
            );

        $sth = $this->connection->prepare("INSERT INTO band (name, date_creation, agency, description, cover) VALUES(:name, :date_creation, :agency, :description, :cover)");
        $sth->bindParam(':name', $name);
        $sth->bindParam(':date_creation', $date);
        $sth->bindParam(':agency', $agency);
        $sth->bindParam(':description', $description);
        $sth->bindParam(':cover', $cover);

        $res = (bool) $sth->execute();

        if(is_bool($res) && $res){
            if(count($params->list) > 0)
                return $this->setBandToUser($params->list, $name);
        }

        return $res;
    }

    /**
     *  Set Band To User
     *              Set the group that a douceur can be
     *  @param Array $list
     */
    public function setBandToUser($lists, $name, $optid = ''){
        if (!is_null($name))
            $band = $this->getBand($name);

        $sql = 'UPDATE douceur SET group_id = :group_id WHERE ';
        foreach($lists as $key => $list){
            if($key === count($lists) - 1)
                $sql .= 'douceur_id = '.$list;
            else
                $sql .= 'douceur_id = '.$list.' OR ';
        }

        $stmt = $this->connection->prepare($sql);

        if (!is_null($name))
            $stmt->bindParam(':group_id', $band[0]['id']);
        else
            $stmt->bindParam(':group_id', $optid);

        return (bool) $stmt->execute();
    }

    /**
     *  Remove Band
     *          Remove a band
     *  @param int $bandID
     *  @return bool execute()
     */
    public function removeBand($bandID){
        // remove the douceur from a group
        $sth = $this->connection->prepare("DELETE FROM band WHERE id = :bandID");
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
    public function updateBand($params){
        $sql = "UPDATE band SET";
        $counter = 0;
        $id = $params->id;

        // remove the band from the update array
        // an other function will update the douceur ...
        if(isset($params->band))
            unset($params->{"band"});

        if(isset($params->id))
            unset($params->{"id"});

        // make the SQL request
        foreach($params as $key=>$param){
            if($key != "band")
                if($counter != count((array) $params) - 1)
                    $sql .= " ".$key."=:".$key.",";
                else
                    $sql .= " ".$key."=:".$key;

            $counter++;
        }

        $sql .= " WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        // Bind the value
        foreach($params as $key=>$value){
            $stmt->bindValue(":".$key, $value);
        }


        // bind the id
        $stmt->bindParam(':id', $id);

        try{
            return (bool) $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage($e);
        }
    }

    public function updateCover($coverPath, $id){
        if(is_null($coverPath))
            return;

        $stmt = $this->connection->prepare('UPDATE band SET cover = :cover WHERE id = :id');
        $stmt->bindParam(':cover', $coverPath);
        $stmt->bindParam(':id', $id);

        try{
            return (bool) $stmt->execute();
        } catch (PDOException $e){
            return $e->getMessage();
        }

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
        $sth = $this->connection->prepare("SELECT * FROM band WHERE name = :bandName");
        $sth->bindParam(':bandName', $bandName);
        $sth->execute();

        return $sth->fetchAll();
    }

    /**
     *  Is Already Present
     *          Check if the group already exist
     *  @param String $name
     *  @return boolean
     */
    public function isAlreadyPresent($name){
        $sth = $this->connection->prepare("SELECT * FROM band WHERE name = :name");
        $sth->bindParam(':name', $name);
        $sth->execute();

        return (bool) $sth->fetchAll();
    }

    /**
     *  Get Artists
     *          Get artist from a band
     *  @param int id
     */
    public function getArtists($id){
        $sth = $this->connection->prepare("SELECT * FROM douceur WHERE group_id = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     *
     */
    public function updateDouceurBand($req){
        $datas = json_decode($req->getParams()['datas']);

        if(isset($datas->band)){
            // update the bands
            if(count($datas->band->delete)){
                // remove the datas
                $this->removeDouceur($datas->band->delete);

            }

            if(count($datas->band->new)){
                $this->setBandToUser($datas->band->new, null, $datas->id);
            }
        }

        return true;
    }

    public function removeDouceur($ids){
        $sql = "UPDATE douceur SET group_id = NULL WHERE ";

        // create the SQL
        foreach($ids as $key=>$id){
            if($key < count($ids) - 1){
                $sql .= " douceur_id = ? OR";
            } else {
                $sql .= " douceur_id = ?";
            }
        }



        $stmt = $this->connection->prepare($sql);
        // bind the params..
        foreach($ids as $key=>$id){
            $bind = explode("douceur_", $id);
            $stmt->bindValue($key+1, $bind[1]);
        }

        try {
            return (bool) $stmt->execute();
        } catch (PDOException $e){
            return $e->getMessage();
        }
    }
}