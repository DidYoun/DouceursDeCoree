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

class App extends PHPUnit_Extensions_Selenium2TestCase
{
    /** @var string ROOT_URL*/
    const ROOT_URL = "/";
    /** @var string DEFAULT_PATTERN_TITLE */
    const DEFAULT_PATTERN_TITLE = "Douceurs de Corée | ";
    /** @var string DEFAULT_TITLE_NOT_FOUND */
    const DEFAULT_TITLE_NOT_FOUND = "Page Not Found";
    /** @var  array $config */
    protected $config;

    /**
     * Launch this method before each method test
     */
    protected function setUp()
    {
        $this->setHost('www.douceurs-coree.dev');
        $this->setBrowserUrl('http://www.douceurs-coree.dev/');
        $this->setBrowser('chrome');
        if (empty($this->config)) {
            $this->config = $this->getSeleniumConfig();
        }
    }

    /**
     * Get root URL
     *
     * @return string
     */
    protected function getRootUrl()
    {
        return self::ROOT_URL;
    }

    /**
     * Clear inputs fields
     *
     * @param array|null $attributes
     * @return bool
     */
    protected function resetInputValues(array $attributes = null)
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

    /**
     * Get selenium configuration
     *
     * @return array
     */
    private function getSeleniumConfig()
    {
        return require ('..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'selenium.php');
    }
}