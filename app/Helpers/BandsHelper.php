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

class BandsHelper
{
    function __construct(){
        $this->band = new Bands();
    }

    /**
     *  Get Bands
     *          Get every bands or a band
     */
    public function getBands($bandName, $all){
        if($all)
            return $this->band->getAllBands();
        
        return $this->band->getBand($bandName);
    }

    public function createBand($request){
        $params = array('name' => $request->getParsedBody()['name'],
                        'length' => $request->getParsedBody()['membersLength'],
                        'date' => $request->getParsedBody()['date'],
                        'agency' => $request->getParsedBody()['agency']);
                        
        $this->band->createBands($params);
    }
}