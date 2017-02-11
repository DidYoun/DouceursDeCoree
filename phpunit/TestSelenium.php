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
}