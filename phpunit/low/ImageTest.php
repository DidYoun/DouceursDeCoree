<?php

/**
 * Class ImageTest
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'AppPageObject.php';

class ImageTest extends AppPageObject
{

    /**
     * Create new douceur with thumbnail and check if we have picture attach to it
     *
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createActionNoMedia()
     * @covers \Image::getItemsIdsFromClass()
     * @covers \Image::getNewestItemId()
     * @covers \Image::checkIfItemHavePicture()
     */
    public function testCreateSweetWithPicture()
    {
        // Render homepage
        $this->url($this->getRootUrl());
        // Get current items in homepage
        $currentSweetItemIds = $this->image->getItemsIdsFromClass(Douceur::DOUCEUR_ITEM_CLASS);
        $this->assertNotFalse($currentSweetItemIds);
        // Render view create new douceur
        $this->assertTrue($this->douceur->createViewAction());
        // Inject form data without picture
        $this->assertTrue($this->douceur->createAction());
        // Check if we have one more sweet
        $newSweetElement = $this->image->getNewestItemId($currentSweetItemIds, Douceur::DOUCEUR_ITEM_CLASS);
        // Target the newest item and check if we have thumbnail
        $this->assertTrue($this->image->checkIfItemHavePicture(reset($newSweetElement)));
    }

    /**
     * Create new douceur without thumbnail
     *
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createActionNoMedia()
     */
    public function testCreateSweetWithoutPicture()
    {
        // Render homepage
        $this->url($this->getRootUrl());
        // Get current items in homepage
        $currentDouceurItems = $this->douceur->countDouceursItems();
        $this->assertNotFalse($currentDouceurItems);
        // Render view create new douceur
        $this->assertTrue($this->douceur->createViewAction());
        // Inject form data without picture
        $this->assertTrue($this->douceur->createActionNoMedia());
        // Check if we have one more sweet
        $this->assertEquals($this->douceur->countDouceursItems(), $currentDouceurItems + 1);
    }

    /**
     * Create new douceur without thumbnail and check if we have default picture attach to it
     *
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createActionNoMedia()
     * @covers \Image::getItemsIdsFromClass()
     * @covers \Image::getNewestItemId()
     * @covers \Image::checkIfItemHavePicture()
     */
    public function testCreateSweetWithDefaultPicture()
    {
        // Render homepage
        $this->url($this->getRootUrl());
        // Get current items in homepage
        $currentSweetItemIds = $this->image->getItemsIdsFromClass(Douceur::DOUCEUR_ITEM_CLASS);
        $this->assertNotFalse($currentSweetItemIds);
        // Render view create new douceur
        $this->assertTrue($this->douceur->createViewAction());
        // Inject form data without picture
        $this->assertTrue($this->douceur->createActionNoMedia());
        // Check if we have one more sweet
        $newSweetElement = $this->image->getNewestItemId($currentSweetItemIds, Douceur::DOUCEUR_ITEM_CLASS);
        // Target the newest item and check if we have thumbnail
        $this->assertFalse($this->image->checkIfItemHavePicture(reset($newSweetElement)));
    }
}