<?php

/**
 * Class Douceur
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Models;

class Douceur extends AbstractDb
{
    /** @var string $table */
    protected $table = "douceur";
    /** @var array $fillables */
    protected $fillables = ["name", "lastname", "age", "description"];

    /**
     * Save new douceur in database
     *
     * @param array $attributes
     * @return bool
     */
    public function save($attributes)
    {
        foreach ($this->fillables as $fillable) {
            if (!isset($attributes[$fillable])) {
                return false;
            }
        }
        $query = $this->connection->prepare("INSERT INTO douceur (name, lastname, age, description, thumbnail) VALUES (:name, :lastname, :age, :description, :thumbnail)");
        $query->bindParam(":name", $attributes["name"]);
        $query->bindParam(":lastname", $attributes["lastname"]);
        $query->bindParam(":age", $attributes["age"]);
        $query->bindParam(":description", $attributes["description"]);
        $query->bindParam(":thumbnail", $attributes["path"]);

        return (bool)$query->execute();
    }

    public function update($attributes)
    {
        if (!isset($attributes["id"]) || !isset($attributes["attributes"]) || !isset($attributes["image_path"])) {
            return false;
        }
        /** @var null $thumbnail */
        $thumbnail = null;
        if ($attributes["image_path"]) {
            $thumbnail = $attributes["image_path"];
        } else {
            /** @var Douceur $item */
            $item = $this->load($this->table, [$attributes["id"]], "thumbnail");
            if (!isset($item) || !is_array($item)) {
                return false;
            }
            /** @var string $thumbnail */
            $item = reset($item);
            if (!isset($item["thumbnail"])) {
                return false;
            }
            $thumbnail = $item["thumbnail"];
        }
        /** @var array $body */
        $body = $attributes["attributes"];
        $query = $this->connection->prepare("UPDATE douceur SET name = :name, lastname = :lastname, age = :age, description = :description, thumbnail = :thumbnail WHERE douceur_id LIKE :id");
        $query->bindParam(":name", $body["name"]);
        $query->bindParam(":lastname", $body["lastname"]);
        $query->bindParam(":age", $body["age"]);
        $query->bindParam(":description", $body["description"]);
        $query->bindParam(":thumbnail", $thumbnail);
        $query->bindParam(":id", $attributes["id"]);

        return (bool)$query->execute();
    }

    /**
     * Delete douceur
     *
     * @param string|null $id
     * @return bool
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return false;
        }
        try {
            $query = $this->connection->prepare("DELETE FROM douceur WHERE douceur_id LIKE :id");
            $query->bindParam(":id", $id);

            return (bool)$query->execute();
        } catch (\PDOException $e) {
            echo ("Error in the delete of the douceur");
        }

        return false;
    }
}