<?php

/**
 * Class Band
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */

use PHPUnit_Extensions_Selenium2TestCase_Keys;

class Band
{
    /** @var array $config */
    public $config;
    /** @var PHPUnit_Extensions_Selenium2TestCase $selenium */
    protected $selenium;

    // Define the data that will use selenium to create a BAND 
    /**
     *  @var string SELENIUM_KEY_BAND_FORM_CREATE
     */
    const SELENIUM_KEY_BAND_FORM_CREATE = "SELENIUM_KEY_BAND_FORM_CREATE";

    /**
     *  @var string SELENIUM_KEY_BAND_FORM_CREATE_INVALID
     */
    const SELENIUM_KEY_BAND_FORM_CREATE_INVALID = "SELENIUM_KEY_BAND_FORM_CREATE_INVALID";

    /**
     *  @var string 
     */ 
    const SELENIUM_KEY_BAND_FORM_EDIT = "SELENIUM_KEY_BAND_FORM_EDIT";

    /**
     *  @var string PATH_BAND_VIEW
     */
    const PATH_BAND_VIEW = "band";

    /** 
     * @var string PATH_BAND_CREATE
     */
    const PATH_BAND_CREATE = "band/new/";

    /**
     * @var string PATH_BAND_EDIT
     */
    const PATH_BAND_EDIT = "band/";

    /**
     *  @var string PATH_BAND_DELETE 
     */
    const PATH_BAND_DELETE = "band/";

    /**
     *  @var string PATH_REMOVE_ALL
     */
    const PATH_BAND_REMOVEALL = "band/removeAll";

    /**
     *  @var string PATH_BAND_UPDATE
     */
    const PATH_BAND_UPDATE = "band/update";


    /**
     * Band constructor.
     *
     * @param PHPUnit_Extensions_Selenium2TestCase $selenium
     */
    public function __construct($selenium)
    {
        $this->selenium = $selenium;
        $this->config = AppPageObject::getSeleniumConfig();
    }

    /**
     *  View Band 
     *     
     *  @return int 
     *  @throws 
     */
    public function viewBand(){
        try {

        } catch(PHPUnit_Extensions_Selenium2TestCase_Exception $e){

        }
    }

    /**
     *  Create Band 
     *      Create band view
     */
    public function createBand(){
        try {
            $this->selenium->url(AppPageObject::getRootUrl());
            $btnCreateBand = $this->selenium->byId('btn-create-bands');
            $btnCreateBand->click();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo ('Band create view is unavailable');
            return false;
        }
    }

    public function actionViewBand(){
        try {

        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){

        }
    }

    /**
     *  Action Create Sweet Band 
     *  
     *  @var string $actions
     *  @return boolean
     */
    public function actionCreateSweetBand($param){

        if ($param == "SELENIUM_KEY_BAND_FORM_CREATE")
            $conf = self::SELENIUM_KEY_BAND_FORM_CREATE;
        else 
            $conf = self::SELENIUM_KEY_BAND_FORM_CREATE_INVALID;
        
        // set the data of the form
        // NAME of the band 
        $this->selenium->byId('name')->value($this->config[$conf]['name']);

        // DATE of the creation of the band 
        $this->selenium->byId('date')->value($this->config[$conf]['date']);

        // AGENCY of the band 
        $this->selenium->byId('agency')->value($this->config[$conf]['agency']);
        
        // Description of the band 
        $this->selenium->byId('group')->value($this->config[$conf]['description']);

        // Cover of the band 
        $fileInput = $this->selenium->byId('fileInput');
        $fileInput->click();
        $fileInput->value($this->config[$conf]['media_path']);


        $createBtn = $this->selenium->byId('create-group');
        $createBtn->click();

        // @TODO pass a string to represent the ACTION to use 
        // wait for a max time of 5sec 
        sleep(5);

        if ($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW == $this->selenium->url())
            return true;

        return false;
    }

    /**
     *  Select sweet band 
     */
    protected function selectSweetBand(){
    
        try{
            $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="band-item"]'));

            if (!isset($items) || !is_array($items)) {
                return false;
            }

            // Take a random key from the list 
            $randomKey = array_rand($items, 1);
            $itemID = $items[$randomKey]->attribute('id');

            // check if the id is in the right format 
            preg_match_all('/_(\d*)$/', $itemID, $matches);
            if (!isset($matches) || !isset($matches[1]) || !isset($matches[1][0])) {
                    return false;
            }
            $identifier = $matches[1][0];
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo ("Can not retrieve a random band");
        }

        return $identifier;
    }

    /**
     *  Select douceur while creating the band 
     */
    public function selectRandomDouceur(){
        try{    
            $this->selenium->waitUntil(function(){
                $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="btn btn-info select"]'));
            
                if (!isset($items) && !is_array($items))
                    return false;
                
                $randomKey = array_rand($items, 1);
                $items[$randomKey]->click();

                return true;
            }, 8000);
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo ('Can not retrieve a douceur for creating a band');
            return false;
        }

        return true;
    }

}