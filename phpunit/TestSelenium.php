<?php

/**
 * Tests for Douceurs Corée using PHPUnit_Extensions_Selenium2TestCase.
 *
 * @package             PHPUnit_Selenium
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>
 * @copyright           Copyright (c) 2016 Tinwork
 * @license             http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link                http://www.phpunit.fr/
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'DriverHelper.php';

class TestSelenium extends DriverHelper
{
    /**
     * Launch this method before each method test ok ?
     */
    protected function setUp()
    {
        $this->setHost('www.douceurs-coree.dev');
        $this->setBrowserUrl('http://www.douceurs-coree.dev/');
        $this->setBrowser('chrome');
    }

    /**
     * Check 404
     */
    public function test404()
    {
        $this->url('/coucou');
        $this->assertEquals(self::DEFAULT_TITLE_NOT_FOUND, $this->title());
    }

    /**
     * User story n°1 : Sébastien visite les douceurs de Corée.
     */
    public function testSebastienVisitLesDouceursDeCoree()
    {
        $this->url('/');
        $this->assertEquals(self::DEFAULT_PATTERN_TITLE . 'Home', $this->title());
    }

    /**
     * User story n°2 : Sébastien visite une douceur de Corée.
     * Road map :
     * --> Render homepage
     * --> Simulate click on item identifier #7
     * --> Check if the url of the browser as the identifier.
     */
    public function testSebastienVisitUneDouceurDeCoree()
    {
        $this->url('/');
        $sweetId = $this->getRandomIdentifierFromSweetItems();
        if (!$sweetId) {
            $this->assertFalse($sweetId);
        } else {
            /** Render view sweet page */
            $this->byId('douceur_' . $sweetId)->click();
            /** Check if we are redirect to sweet page view */
            $this->assertEquals($this->getBrowserUrl() . 'douceur/' . $sweetId, $this->url());
        }
    }

    /**
     * User story n°3 : Sébastien crée une nouvelle douceur de Corée.
     * Road map :
     * --> Get the current items in homepage
     * --> Render page create
     * --> Inject data in form and submit
     * --> Return to homepage
     * --> Check if nb items = current items + 1
     */
    public function testSebastienCreateNewDouceurDeCoree()
    {
        /** Get current items in homepage */
        $currentDouceurItems = $this->countDouceursItems();
        /** Render view page create */
        $this->assertEquals($this->renderDouceurCreateView(), true);
        /** Inject data in form and submit */
        $this->byName('name')->value("Kim");
        $this->byName('lastname')->value("Jei");
        $this->byName('age')->value("27");
        $this->byName('description')->value("La plus belle");
        //$this->byName('file')->value('va:\Images\Jei\jei.jpg');
        $this->byId('douceur-form-create')->submit();
        /** Check if homepage got current items + 1 */
        $this->assertEquals($this->countDouceursItems(), $currentDouceurItems + 1);
    }
}