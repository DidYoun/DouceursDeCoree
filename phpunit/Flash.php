<?php

/**
 * Class Flash
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */

class Flash
{
    /** @var array $config */
    public $config;
    /** @var PHPUnit_Extensions_Selenium2TestCase $selenium */
    protected $selenium;

    /**
     * Flash constructor.
     *
     * @param PHPUnit_Extensions_Selenium2TestCase $selenium
     */
    public function __construct($selenium)
    {
        $this->selenium = $selenium;
        $this->config = AppPageObject::getSeleniumConfig();
    }


}