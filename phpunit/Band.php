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

class Band
{
    /** @var array $config */
    public $config;
    /** @var PHPUnit_Extensions_Selenium2TestCase $selenium */
    protected $selenium;
    /**
     * @var string MAX_TIME_BEFORE_TIMEOUT
     */
    const MAX_TIME_BEFORE_TIMEOUT = 3000;
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
    const PATH_BAND_REMOVE_ALL = "band/removeAll";
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
     * Render the view of all bands
     *
     * @return bool
     */
    public function view()
    {
        try {
            $this->selenium->url($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW);
            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant retrieve the bands view';
        }
        return false;
    }

    /**
     * Delete action on band view
     *
     * @return bool
     */
    public function delete()
    {
        try {
            $button = $this->selenium->byId('removeGroup');
            $button->click();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant process the delete of the band item';
        }

        return false;
    }

    /**
     *  Action create sweet Band
     *
     *  @var bool $isValid
     *  @return boolean
     */
    public function createAction($isValid)
    {
        try {
            /** @var string $conf */
            $conf = self::SELENIUM_KEY_BAND_FORM_CREATE;
            if (!$isValid) {
                $conf = self::SELENIUM_KEY_BAND_FORM_CREATE_INVALID;
            }
            // NAME of the band
            $this->selenium->byId('name')->value(substr(uniqid($this->config[$conf]['name']), 6));
            // DATE of the creation of the band
            $this->selenium->byId('date')->value($this->config[$conf]['date']);
            // AGENCY of the band
            $this->selenium->byId('agency')->value($this->config[$conf]['agency']);
            // Description of the band
            $this->selenium->byId('group')->value($this->config[$conf]['description']);
            // Cover of the band
            $this->selenium->byName('file')->value($this->config[$conf]['media_path']);

            $createBtn = $this->selenium->byId('create-group');
            $createBtn->click();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant create new band';
        }

        return false;
    }

    /**
     *  False Url 
     *  @return boolean
     */
    public function falseUrl(){
        if ($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW  == $this->selenium->url())
            return true;

        return false;
    }

    /**
     * Render the view page of one band
     *
     * @return bool|int
     */
    public function renderBandItemView()
    {
        try {
            $itemId = $this->_getRandomBandItemFromGrid();
            $bandLink = $this->selenium->element($this->selenium->using('css selector')->value('#band-' . $itemId .' .caption a'));
            if (!isset($bandLink)) {
                return false;
            }
            $bandLink->click();
            return $itemId;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'cant load the band view';
        }

        return false;
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

    /**
     *  Add Douceur Existing Band 
     */
    public function addDouceurExistingBand(){
        $modalButton = $this->selenium->byId('add');
        $modalButton->click();

        // wait for the modal to load 
        sleep(3);
        // add a random douceur
        $this->selectRandomDouceur();

        
        $process = $this->selenium->waitUntil(function(){
            $closeModal = $this->selenium->byId('close-modal');
            
            if (isset($closeModal)){
                $closeModal->click();

                sleep(5);

                $updateBtn = $this->selenium->byId('update');
                $updateBtn->click();

                sleep(5);

                // wait for the page to load...
                // confirm the selection by updating the band
                if ($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW == $this->selenium->url())
                    return true;

                return false;
            }
                    
        }, 1000);

        

        return $process;
    }

    /**
     *  Delete Random Douceur From Band 
     */
    public function deleteRandomDouceurFromBand(){
        try {
            $this->selectRandomDouceur('btn btn-info delete');
            // update now 

            $updateBtn = $this->selenium->byId('update');
            $updateBtn->click();

            sleep(5);

            if ($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW == $this->selenium->url())
                return true;

            return false;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo $e;
        }
    }

    /**
     *  View Random Band 
     *      Select a random band and see it 
     */
    public function viewRandomBand(){
        try {
            $this->selenium->url(AppPageObject::getRootUrl());
             // viewt the band 
            $id = $this->selectSweetBand('btn btn-primary btn-info');
            // Sleep the process 5 sec then check the url 
            sleep(5);

            if ($this->selenium->getBrowserUrl() . self::PATH_BAND_EDIT . $id == $this->selenium->url())
                return true;

            return false;
        } catch(PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            return false;
        }
    }

    /**
     *  Select sweet band 
     *      View a random Band 
     */
    protected function selectSweetBand($selectorID = ''){

        try{
            $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="btn btn-primary btn-info"]'));

    
            if (!isset($items) || !is_array($items)) {
                return false;
            }

            // Take a random key from the list 
            $randomKey = array_rand($items, 1);
            $groupID = $items[$randomKey]->attribute('data-id');
            $items[$randomKey]->click();

            if(isset($groupID))
                return $groupID;
            
            return false;

            // check if the id is in the right format 
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo ("Can not retrieve a random band");
        }
    }

    /**
     *  Select douceur while creating the band
     */
    public function selectRandomDouceur($del = NULL){
        try {
            if(is_null($del))
                $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="btn btn-info select"]'));
            else {
                $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="btn btn-info delete"]'));
            }

            if (empty($items)) {
                throw new PHPUnit_Extensions_Selenium2TestCase_Exception('Any sweety can be add');
            }
            $randomKey = array_rand($items, 1);
            $item = $items[$randomKey];
            $item->click();
            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e){
            echo ('Can not retrieve a douceur for creating a band');
        }

        return false;
    }

    /**
     * Check if we have one sweet which are not include in band
     *
     * @return bool
     */
    public function checkAvailableSweety()
    {
        $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="btn btn-info select"]'));
        if (empty($items)) {
            return false;
        }

        return true;
    }

    /**
     * Return the current number of band items
     *
     * @return int
     */
    public function countCurrentBandItems()
    {
        $this->selenium->url($this->selenium->getBrowserUrl() . self::PATH_BAND_VIEW);
        $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="thumbnail band-item"]'));
        if (empty($items)) {
            return 0;
        }
        return sizeof($items);
    }

    /**
     * From the view page [/band] we retrieve one item randomly
     *
     * @return bool|int
     */
    protected function _getRandomBandItemFromGrid()
    {
        try {
            /** @var array $bands */
            $bands = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="thumbnail band-item"]'));
            if (!isset($bands) || !is_array($bands) || !$bands) {
                return false;
            }
            if (sizeof($bands) <= 1) {
                $band = reset($bands);
                $itemId = $band->attribute('id');
            } else {
                /** @var int $randomKey */
                $randomKey = array_rand($bands, 1);
                /** @var string $itemId */
                $itemId = $bands[$randomKey]->attribute('id');
            }
            preg_match(AppPageObject::PATTERN_EXTRACT_ID_FROM_ID, $itemId, $matches);
            if (!isset($matches) || !isset($matches['id'])) {
                return false;
            }

            return $matches['id'];
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'cant retrieve an random band id';
        }
    }
}