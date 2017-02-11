<?php

/**
 * Class BandsHelper
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/CRUDSelenium
 */
namespace Helpers;

use Models\Bands;
use DateTime;

class BandsHelper
{
    function __construct(){
        $this->band = new Bands();
    }

    /**
     *  Get Bands
     *          Get every bands or a band
     */
    public function getBands($bandName){
        return $this->band->getBand($bandName);
    }

    /**
     *  Create Band
     *          Create a band based on the data entered
     *  @param HTTP Request $request
     *  @return Boolean
     */
    public function createBand($request){
        $upload = new UploadHelper();
        $uploadPath = $upload->upload($request->getUploadedFiles());
        $params = json_decode($request->getParams()['datas']);


        return $this->band->createBand($params, $uploadPath);
    }

    /**
     *  Get All Bands
     *          Return every bands
     *  @return array $band
     */
    public function getAllBands(){
        return $this->band->getAllBands();
    }

    /**
     *  Get Artist From Bands
     *
     */
    public function getArtistFromBands($id){
        return $this->band->getArtists($id);
    }

    /**
     *  Remove Band
     *      Remove a band based on it's id
     *  @param Int id
     */
    public function removeBand($id){
        // get the douceur of a group
        return $this->band->removeBand($id);
    }

    /**
     *  Update Band
     *          Update a band based on it's id and the params
     *  @param HTTP Request
     *  @return Boolean
     */
    public function update($request){
        $upload = new UploadHelper();
        $uploadPath = $upload->upload($request->getUploadedFiles());
        $data = json_decode($request->getParams()['datas']);

        if($uploadPath)
            $this->band->updateCover($uploadPath, $data->id);

        if(count((array) $data) > 1)
            $this->band->updateBand($data);

        // update the douceur
        return $this->band->updateDouceurBand($request);
    }

    /**
     *  Get Date
     *          Get a date based on a str date
     *  @param String $strDate
     *  @return date $date
     */
    public function getDate($strDate){
        $date = new DateTime($strDate);

        return date_format($date, "Y-m-d");
    }

    public function removeAllDouceur($request){
        $ids = $request->getParsedBody()['delete'];
        return $this->band->removeDouceur($ids);
    }
}