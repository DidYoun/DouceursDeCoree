<?php

/**
 * Class AppTest
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'AppPageObject.php';

class AppTest extends AppPageObject
{
    /**
     * Check 404
     *
     * @coversNothing
     */
    public function test404()
    {
        $this->url('/coucou');
        $this->assertEquals(self::DEFAULT_TITLE_NOT_FOUND, $this->title());
    }

    /**
     * Sébastien visite les douceurs de Corée.
     *
     * @coversNothing
     */
    public function testSebastienVisitLesDouceursDeCoree()
    {
        $this->url('/');
        $this->assertEquals(self::DEFAULT_PATTERN_TITLE . 'Home', $this->title());
    }
}