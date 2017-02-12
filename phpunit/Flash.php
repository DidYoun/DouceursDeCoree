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
     * @var string FLASH_STATE_SUCCESS
     */
    const FLASH_STATE_SUCCESS = "alert-success";
    /**
     * @var string FLASH_STATE_INFO
     */
    const FLASH_STATE_INFO = "alert-info";
    /**
     * @var string FLASH_STATE_ERROR
     */
    const FLASH_STATE_ERROR = "alert-error";

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

    /**
     * Check the flash state
     *
     * @param string $expectedState
     * @return bool
     */
    public function getFlashMessage($expectedState)
    {
        try {
            /** @var array $flash */
            $flash = $this->selenium->byClassName('alert');
            if (!isset($flash)) {
                return false;
            }
            /** @var string $classList */
            $classList = $flash->attribute('class');
            /** @var array $classList */
            $classList = explode(' ', $classList);
            foreach ($classList as $className) {
                if ($className !== $expectedState) {
                    continue;
                }

                return true;
            }
            return false;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant retrieve the class list of the target item';
        }

        return false;
    }
}