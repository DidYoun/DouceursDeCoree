<?php

/**
 * Class Image
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */

class Image
{
    /**
     * @var string PATTERN_EXTRACT_PICTURE_NAME
     */
    const PATTERN_EXTRACT_PICTURE_NAME = "/[\|\/]?(?P<picture_name>\w*\.[a-z]{2,3}$)/i";
    /**
     * @var string DEFAULT_PICTURE_LABEL
     */
    const DEFAULT_PICTURE_LABEL = "default.png";
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


    /**
     * Get sweet ids
     *
     * @param string $class
     * @return bool|array
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function getItemsIdsFromClass($class)
    {
        /** @var array $ids */
        $ids = [];
        try {
            /** @var array $items */
            $items = $this->selenium->elements($this->selenium->using('css selector')->value('*[class="' . $class .'"]'));
            if (!isset($items) || !is_array($items) || sizeof($items) < 1) {
                return false;
            }
            foreach ($items as $item) {
                /** @var string $itemId */
                $itemId = $item->attribute('id');
                if (!isset($itemId)) {
                    continue;
                }
                $ids[] = $itemId;
            }

            return $ids;
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant retrieve the ids of sweets';
        }

        return false;
    }

    /**
     * Compare array of sweets ids and return the newest
     *
     * @param array $ids
     * @param string $class
     * @return array|bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function getNewestItemId($ids, $class)
    {
        try {
            if (!isset($ids) || !is_array($ids)) {
                return false;
            }
            /** @var array $newIds */
            $newIds = $this->getItemsIdsFromClass($class);
            return array_diff($newIds, $ids);
        } catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            echo 'Cant retrieve the newest id of sweet';
        }

        return false;
    }

    /**
     * Check if item has picture
     *
     * @param string $itemId
     * @return bool
     * @throws PHPUnit_Extensions_Selenium2TestCase_Exception
     */
    public function checkIfItemHavePicture($itemId)
    {
        if (!isset($itemId) || !is_string($itemId)) {
            throw new PHPUnit_Extensions_Selenium2TestCase_Exception('Undefined sweet id');
        }
        /** @var array $element */
        $element = $this->selenium->element($this->selenium->using('css selector')->value('#' . $itemId .' .front > img'));
        if (!isset($element)) {
            throw new PHPUnit_Extensions_Selenium2TestCase_Exception('No item');
        }
        preg_match(self::PATTERN_EXTRACT_PICTURE_NAME, $element->attribute('src'), $matches);
        if (!isset($matches) || !isset($matches['picture_name']) || $matches['picture_name'] == self::DEFAULT_PICTURE_LABEL) {
            return false;
        }

        return true;
    }
}