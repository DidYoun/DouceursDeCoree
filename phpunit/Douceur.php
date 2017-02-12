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
    /** @var array $config */
    public $config;
    /** @var PHPUnit_Extensions_Selenium2TestCase $selenium */
    protected $selenium;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo('Unavailable douceur view');
        }

        return $sweetId;
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

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger POST on form failed');
        }

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Trigger POST on form failed';
        }

        return true;
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
            $this->selenium->byName('name')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['name']);
            $this->selenium->byName('lastname')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['lastname']);
            $this->selenium->byName('age')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['age']);
            $this->selenium->byName('description')->value($this->config[self::SELENIUM_KEY_FAILED_FORM_CREATE]['description']);
            $this->selenium->byName('file')->value(null);
            $this->selenium->byId('douceur-form-create')->submit();
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Trigger POST on form failed';
        }

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Unavailable view edit';
        }

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger submit on edit form has failed');
        }

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Trigger click on delete button has failed');
        }

        return true;
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
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Can not count the current douceurs on page');
        }

        return sizeof($items);
    }

    /**
     * Get random identifier of sweet
     *
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function getRandomIdentifierFromSweetItems()
    {
        /** @var null $identifier */
        $identifier = null;
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
            preg_match_all('/_(\d*)$/', $itemId, $matches);
            if (!isset($matches) || !isset($matches[1]) || !isset($matches[1][0])) {
                return false;
            }
            $identifier = $matches[1][0];
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo ('Cant retrieve an random douceur identifier');
        }

        return $identifier;
    }
}