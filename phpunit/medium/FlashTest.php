<?php

/**
 * Class FlashTest
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'AppPageObject.php';

class FlashTest extends AppPageObject
{
    /**
     * Check if we got flash message after douceur create action
     *
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createAction()
     * @covers \Flash::getFlashMessage(Flash::FLASH_STATE_SUCCESS)
     */
    public function testFlashOnSweetCreate()
    {
        // Render to the create douceur view
        $this->assertTrue($this->douceur->createViewAction());
        // Create new douceur
        $this->assertTrue($this->douceur->createAction());
        // Check if we have flash message with correct state
        $this->assertTrue($this->flash->getFlashMessage(Flash::FLASH_STATE_SUCCESS));
    }

    /**
     * Check if we got flash message after douceur edit action
     *
     * @covers \Douceur::view()
     * @covers \Douceur::editViewAction()
     * @covers \Douceur::editAction()
     * @covers \Flash::getFlashMessage(Flash::FLASH_STATE_SUCCESS)
     */
    public function testFlashOnSweetEdit()
    {
        // Render the view of one douceur
        $targetItem = $this->douceur->view();
        $this->assertNotFalse($targetItem);
        // Trigger edit view from button
        $this->assertTrue($this->douceur->editViewAction());
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_EDIT . $targetItem, $this->url());
        // Clear one input field for test
        $this->assertTrue($this->resetInputValues(["name"]));
        // Inject new data to form EDIT
        $this->assertTrue($this->douceur->editAction());
        // Check if we are redirect to view page
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $targetItem, $this->url());
        // Check if we have flash message with correct state
        $this->assertTrue($this->flash->getFlashMessage(Flash::FLASH_STATE_SUCCESS));
    }

    /**
     * Check if we got flash message after douceur create action
     *
     * @covers \Douceur::view()
     * @covers \Douceur::deleteAction()
     * @covers \Flash::getFlashMessage(Flash::FLASH_STATE_SUCCESS)
     */
    public function testFlashOnSweetDelete()
    {
        $this->url($this->getRootUrl());
        // Render the view of one douceur
        $targetItem = $this->douceur->view();
        $this->assertNotFalse($targetItem);
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $targetItem, $this->url());
        // Trigger event delete on button from douceur view
        $this->assertTrue($this->douceur->deleteAction());
        /** Check if we're redirect on homepage */
        $this->assertEquals($this->getBrowserUrl(), $this->url());
       // Check if we have flash message with correct state
        $this->assertTrue($this->flash->getFlashMessage(Flash::FLASH_STATE_SUCCESS));
    }
}