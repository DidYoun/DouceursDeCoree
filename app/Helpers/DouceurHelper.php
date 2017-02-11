<?php

/**
 * Class DouceurHelper
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 DidYoun
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Helpers;

use Models\Douceur;

class DouceurHelper
{
    /** @var null $douceur */
    protected $douceur = null;

    public function __construct()
    {
        $this->douceur = new Douceur();
    }

    /**
     * Load all the douceurs
     *
     * @return bool|array
     */
    public function getDouceurs()
    {
        try {
            /** @var array $douceurs */
            $douceurs = $this->douceur->load("douceur");
            if (!isset($douceurs) || !is_array($douceurs) || sizeof($douceurs) < 1) {
                return false;
            }
            return $douceurs;
        } catch (\PDOException $e) {
            echo ("Error in the recovering of all the douceurs");
        }

        return false;
    }

    /**
     * Load douceur by id
     *
     * @param null $id
     * @return Douceur|bool
     */
    public function getDouceur($id = null)
    {
        if (!isset($id)) {
            return false;
        }
        try {
            /** @var \Models\Douceur $douceur */
            $douceur = $this->douceur->load("douceur", [$id]);
            if (!isset($douceur) || !is_array($douceur) || sizeof($douceur) < 1) {
                return false;
            }
            return reset($douceur);
        } catch (\PDOException $e) {
            echo ("Error in the recovering the douceur");
        }

        return false;
    }

    /**
     * Check if empty field
     *
     * @param array $attributes
     * @return bool
     */
    public function beforeSave($attributes)
    {
        /** @var string $attribute */
        foreach ($attributes as $attribute) {
            if (!isset($attribute)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create new douceur
     *
     * @param array $attributes
     * @return bool
     */
    public function create($attributes)
    {
        if (!isset($attributes["upload"]) || !isset($attributes["body"])) {
            return false;
        }
        /** @var null $douceurId */
        $douceurId = null;
        if (!$this->beforeSave($attributes["body"])) {
            return false;
        }
        $uploadHelper = new UploadHelper();
        /** @var null|bool $uploadPath */
        $uploadPath = $uploadHelper->upload($attributes["upload"]);
        if (!$uploadPath) {
            $uploadPath = $uploadHelper::PATH_DEFAULT_IMAGE;
        }
        if (!$this->douceur->save([
            "path"          => $uploadPath,
            "name"          => $attributes["body"]["name"],
            "lastname"      => $attributes["body"]["lastname"],
            "age"           => $attributes["body"]["age"],
            "description"   => $attributes["body"]["description"]
        ])) {
            return false;
        }

        return true;
    }

    /**
     * Update douceur
     *
     * @param $attributes
     * @return bool|Douceur
     */
    public function updateDouceur($attributes)
    {
        if (!isset($attributes["id"]) || !isset($attributes["body"]) || !isset($attributes["upload"])) {
            return false;
        }
        /** @var \Helpers\UploadHelper $uploadHelper */
        $uploadHelper = new UploadHelper();
        /** @var null|bool $uploadPath */
        $uploadPath = $uploadHelper->upload($attributes["upload"]);
        /** @var \Models\Douceur $douceur */
        $douceur = $this->douceur->update([
            "id"            => $attributes["id"],
            "attributes"    => $attributes["body"],
            "image_path"    => $uploadPath
        ]);
        if (!isset($douceur) || !$douceur) {
            return false;
        }

        return $douceur;
    }

    /**
     * Delete douceur
     *
     * @param string|null $id
     * @return bool
     */
    public function deleteDouceur($id)
    {
        if (!isset($id)) {
            return false;
        }

        return $this->douceur->delete($id);
    }
}