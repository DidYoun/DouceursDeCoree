<?php

/**
 * Class Douceur
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
class Douceur
{
    /**
     * @var string PATTERN_EXTRACT_ID_FROM_ID
     */
    const PATTERN_EXTRACT_ID_FROM_ID = '/_(?<douceur_id>[0-9]+)$/i';
    /**
     * @var string SELENIUM_KEY_VALID_FORM_CREATE
     */
    const SELENIUM_KEY_VALID_FORM_CREATE = "SELENIUM_FORM_CREATE_SWEET_VALID_DATA";
    /**
     * @var string SELENIUM_KEY_FAILED_FORM
     */
    const SELENIUM_KEY_FAILED_FORM_CREATE = "SELENIUM_FORM_CREATE_SWEET_FAILED_DATA";
    /**
     * @var string SELENIUM_KEY_VALID_FORM_EDIT
     */
    const SELENIUM_KEY_VALID_FORM_EDIT = "SELENIUM_FORM_EDIT_SWEET_DATA";
    /**
     * @var string PATH_DOUCEUR_VIEW
     */
    const PATH_DOUCEUR_VIEW = "douceur/";
    /**
     * @var string PATH_DOUCEUR_CREATE
     */
    const PATH_DOUCEUR_CREATE = "douceur/new";
    /**
     * @var string PATH_DOUCEUR_EDIT
     */
    const PATH_DOUCEUR_EDIT = "douceur/edit/";
    /**
     * @var string PATH_DOUCEUR_DELETE
     */
    const PATH_DOUCEUR_DELETE = "douceur/delete/";
    /**
     * @var string DOUCEUR_ITEM_CLASS
     */
    const DOUCEUR_ITEM_CLASS = "douceur-item";
    /** @var array $config */
    public $config;
    /** @var PHPUnit_Extensions_Selenium2TestCase $selenium */
    protected $selenium;

    /**
     * Douceur constructor.
     *
     * @param PHPUnit_Extensions_Selenium2TestCase $selenium
     */
    public function __construct($selenium)
    {
        $this->selenium = $selenium;
        $this->config = AppPageObject::getSeleniumConfig();
    }

    /**
     * Render douceur view
     *
     * @return int
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function view()
    {
        /** @var null $sweetId */
        $sweetId = null;
        try {
            $this->selenium->url(AppPageObject::getRootUrl());
            /** @var int $sweetId */
            $sweetId = $this->getRandomIdentifierFromSweetItems();
            /** Render view page of sweet */
            $this->selenium->byId('douceur_' . $sweetId)->click();

            return $sweetId;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo('Unavailable douceur view');
        }

        return false;
    }

    /**
     * Render view create
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function createViewAction()
    {
        try {
            $this->selenium->url(AppPageObject::getRootUrl());
            $btnCreateNewSweet = $this->selenium->byId('btn-create');
            $btnCreateNewSweet->click();
            if ($this->selenium->getBrowserUrl() . self::PATH_DOUCEUR_CREATE == $this->selenium->url()) {
                return true;
            }
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo('Unavailable view create');
        }

        return false;
    }

    /**
     * Create new douceur
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function createAction()
    {
        try {
            /** Inject data in form and submit */
            $this->selenium->byName('name')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['name']);
            $this->selenium->byName('lastname')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['lastname']);
            $this->selenium->byName('age')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['age']);
            $this->selenium->byName('description')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['description']);
            $this->selenium->byName('file')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['media_path']);
            $this->selenium->byId('douceur-form-create')->submit();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger POST on form failed');
        }

        return false;
    }

    /**
     * Create new douceur
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function createActionFail()
    {
        try {
            /** Inject data in form and submit */
            $this->selenium->byName('name')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['name']);
            $this->selenium->byName('lastname')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['lastname']);
            $this->selenium->byName('age')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['age']);
            $this->selenium->byName('description')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['description']);
            $this->selenium->byName('file')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['media_path']);
            $this->selenium->byId('douceur-form-create')->submit();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Trigger POST on form failed';
        }

        return false;
    }

    /**
     * Create new douceur without picture
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function createActionNoMedia()
    {
        try {
            /** Inject data in form and submit */
            $this->selenium->byName('name')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['name']);
            $this->selenium->byName('lastname')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['lastname']);
            $this->selenium->byName('age')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['age']);
            $this->selenium->byName('description')->value($this->config[self::SELENIUM_KEY_VALID_FORM_CREATE]['description']);
            $this->selenium->byName('file')->value(null);
            $this->selenium->byId('douceur-form-create')->submit();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Trigger POST on form failed';
        }

        return false;
    }

    /**
     * Render view edit
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function editViewAction()
    {
        try {
            $this->selenium->byId('btn-edit')->click();
            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Unavailable view edit';
        }

        return false;
    }

    /**
     * Edit douceur
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function editAction()
    {
        try {
            $this->selenium->byName('name')->value($this->config[self::SELENIUM_KEY_VALID_FORM_EDIT]['name']);
            $this->selenium->byName('file')->value($this->config[self::SELENIUM_KEY_VALID_FORM_EDIT]['media_path']);
            /** Trigger submit on form */
            $this->selenium->byId('douceur-form-edit')->submit();

            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger submit on edit form has failed');
        }

        return false;
    }

    /**
     * Delete douceur
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function deleteAction()
    {
        try {
            $this->selenium->byId('btn-delete')->click();
            return true;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger click on delete button has failed');
        }

        return false;
    }

    /**
     * Count current items in homepage
     *
     * @return int|bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function countDouceursItems()
    {
        /** @var null $items */
        $items = null;
        try {
            $this->selenium->url(AppPageObject::getRootUrl());
            /** @var array $items */
            $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="douceur-item"]'));
            if (!isset($items) || !is_array($items)) {
                return 0;
            }
            return sizeof($items);
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Can not count the current douceurs on page');
        }

        return false;
    }

    /**
     * Get random identifier of sweet
     *
     * @return bool|string
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function getRandomIdentifierFromSweetItems()
    {
        try {
            /** @var array $items */
            $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="douceur-item"]'));
            if (!isset($items) || !is_array($items) || sizeof($items) < 1) {
                return false;
            }
            /** @var int $randomKey */
            $randomKey = array_rand($items, 1);
            /** @var string $itemId */
            $itemId = $items[$randomKey]->attribute('id');
            preg_match(self::PATTERN_EXTRACT_ID_FROM_ID, $itemId, $matches);
            if (!isset($matches) || !isset($matches['douceur_id'])) {
                return false;
            }

            return $matches['douceur_id'];
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Cant retrieve an random douceur identifier');
        }

        return false;
    }
}