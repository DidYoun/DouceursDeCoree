<?php

/**
 * Class App
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once 'Douceur.php';
require_once 'Band.php';
require_once 'Flash.php';
require_once 'Image.php';

class AppPageObject extends PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * @var string ROOT_URL
     */
    const ROOT_URL = "/";
    /**
     * @var string DEFAULT_PATTERN_TITLE
     */
    const DEFAULT_PATTERN_TITLE = "Douceurs de Corée | ";
    /**
     * @var string DEFAULT_TITLE_NOT_FOUND
     */
    const DEFAULT_TITLE_NOT_FOUND = "Page Not Found";
    /** @var Douceur $douceur */
    protected $douceur;
    /** @var Band $band */
    protected $band;
    /** @var Flash $flash */
    protected $flash;
    /** @var Image $image */
    protected $image;

    /**
     * Launch this method before each method test
     */
    protected function setUp()
    {
        $this->setHost('www.douceurs-coree.dev');
        $this->setBrowserUrl('http://www.douceurs-coree.dev/');
        $this->setBrowser('chrome');
        if (!isset($this->douceur)) {
            $this->douceur = new Douceur($this);
        }
        if (!isset($this->band)) {
            $this->band = new Band($this);
        }
        if (!isset($this->flash)) {
            $this->flash = new Flash($this);
        }
        if (!isset($this->image)) {
            $this->image = new Image($this);
        }
    }

    /**
     * Get root URL
     *
     * @return string
     */
    public static function getRootUrl()
    {
        return self::ROOT_URL;
    }

    /**
     * Get selenium configuration
     *
     * @return array
     */
    public static function getSeleniumConfig()
    {
        return require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'selenium.php');
    }

    /**
     * Clear inputs fields
     *
     * @param array|null $attributes
     * @return bool
     */
    public function resetInputValues(array $attributes = null)
    {
        /** @var string $attribute */
        foreach ($attributes as $attribute) {
            /** @var array $element */
            $element = $this->byName($attribute);
            if (!isset($element)) {
                continue;
            }
            $element->clear();
        }

        return true;
    }
}