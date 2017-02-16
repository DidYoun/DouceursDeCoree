<?php

/**
 * Class BandTest
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'AppPageObject.php';

class BandTest extends AppPageObject
{
    /**
     * Create new band group
     *
     * @covers \Band::countCurrentBandItems()
     * @covers \Band::createBand()
     * @covers \Band::checkAvailableSweety()
     * @covers \Band::selectRandomDouceur()
     * @covers \Band::createAction()
     * @covers \Band::view()
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createAction()
     */
    public function testUserCreateBandWithSweeties()
    {
        $this->url($this->getRootUrl());
        $currentItem = $this->band->countCurrentBandItems();
        $this->assertNotFalse($currentItem);
        $this->assertTrue($this->band->createBand());
        $bandCreateUrl = $this->url();
        if (!$this->band->checkAvailableSweety()) {
            $this->assertTrue($this->douceur->createViewAction());
            $this->assertTrue($this->douceur->createAction());
            $this->url($bandCreateUrl);
        }
        $this->assertTrue($this->band->checkAvailableSweety());
        $this->assertTrue($this->band->selectRandomDouceur());
        $this->assertTrue($this->band->createAction(true));
        $this->assertTrue($this->band->view());
        $this->assertEquals($this->band->countCurrentBandItems(), $currentItem + 1);
    }
}