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
    public function testUserCreateGroupDouceur(){
        $this->url($this->getRootUrl());        
        // go to an other page 
        $this->assertEquals($this->band->createBand(), true);

        // select a douceur 
        $this->assertEquals($this->band->selectRandomDouceur(), true);

        // create a group based on params 
        $this->assertEquals($this->band->actionCreateSweetBand('SELENIUM_KEY_BAND_FORM_CREATE'), true);
    }

    public function testUserCreateBadGrupDouceur(){
        $this->url($this->getRootUrl());
        // go to an other page 
        $this->assertEquals($this->band->createBand(), true);

        // select a douceur 
        $this->assertEquals($this->band->selectRandomDouceur(), true);

        // create a group based on params 
        $this->assertEquals($this->band->actionCreateSweetBand('SELENIUM_KEY_BAND_FORM_CREATE_INVALID'), false);
    }
}