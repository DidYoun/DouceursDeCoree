<?php

/**
 * Class DouceurTest
 *
 * @package             PHPUnit / PHPUnit_Extensions_Selenium2TestCase
 * @author              Didier Youn <didier.youn@gmail.com>, Marc Intha-Amnouay <marc.inthaamnouay@gmail.com>, Antoine Renault <antoine.renault.mmi@gmail.com>
 * @copyright           Copyright (c) 2017 Tinwork
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                https://github.com/DidYoun/DouceursDeCoree/
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'AppPageObject.php';

class DouceurTest extends AppPageObject
{
    /**
     * Render the view page of one sweet item
     *
     * @covers \Douceur::view()
     */
    public function testSebastienVisitUneDouceurDeCoree()
    {
        $this->url($this->getRootUrl());
        $sweetId = $this->douceur->view();
        $this->assertNotFalse($sweetId);
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $sweetId, $this->url());
    }

    /**
     * Sébastien crée une nouvelle douceur de Corée.
     *
     * @covers \Douceur::countDouceursItems()
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createAction()
     */
    public function testSebastienCreateNewDouceurDeCoree()
    {
        /** Get current items in homepage */
        $currentDouceurItems = $this->douceur->countDouceursItems();
        /** Render view page create */
        $this->assertEquals($this->douceur->createViewAction(), true);
        /** Inject data in form and submit */
        $this->assertEquals($this->douceur->createAction(), true);
        /** Check if homepage got current items + 1 */
        $this->assertEquals($this->douceur->countDouceursItems(), $currentDouceurItems + 1);
    }

    /**
     * Sébastien crée une nouvelle douceur de Corée avec de mauvaises données
     *
     * @covers \Douceur::createViewAction()
     * @covers \Douceur::createActionFail()
     */
    public function testSebastienCreateNewDouceurDeCoreeWithWrongFieldValue()
    {
        /** Render view page create */
        $this->assertEquals($this->douceur->createViewAction(), true);
        /** Inject failed data in form and submit */
        $this->assertEquals($this->douceur->createActionFail(), true);
        /** Get an instance of the input submit */
        $btnSubmit = $this->byId('btn-new-douceur');
        /** As we inject wrong data, check if input is disabled */
        $this->assertContains('disabled', $btnSubmit->attribute('class'));
    }

    /**
     * Sébastien met à jour une douceur de Corée.
     *
     * @covers \Douceur::view()
     * @covers \Douceur::editViewAction()
     * @covers \Douceur::editAction()
     * @covers \AppPageObject::resetInputValues()
     */
    public function testSebastienEditOneDouceurDeCoree()
    {
        /** @var string $targetSweetId */
        $targetSweetId = $this->douceur->view();
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $targetSweetId, $this->url());
        /** Trigger event on button [EDIT] */
        $this->assertTrue($this->douceur->editViewAction());
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_EDIT . $targetSweetId, $this->url());
        /** Clear one input field for test */
        $this->assertTrue($this->resetInputValues(["name"]));
        /** Inject new data to form EDIT */
        $this->assertTrue($this->douceur->editAction());
        /** Check if we are redirect to view page */
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $targetSweetId, $this->url());
        /** Select paragraph elem for check the result of edit action */
        $elem = $this->element($this->using('css selector')->value('span[class="douceur-name"]'));
        /** Check if data has been updated */
        $this->assertEquals($elem->text(), $this->douceur->config[Douceur::SELENIUM_KEY_VALID_FORM_EDIT]['name']);
    }

    /**
     * Sébastien supprime une douceur de Corée.
     *
     * @covers \Douceur::countDouceursItems()
     * @covers \Douceur::view()
     * @covers \Douceur::deleteAction()
     */
    public function testSebastienDeleteOneDouceurDeCoree()
    {
        $this->url($this->getRootUrl());
        /** Save the current items on homepage */
        $currentDouceurItems = $this->douceur->countDouceursItems();
        $this->assertNotFalse($currentDouceurItems);
        /** @var string $targetSweetId */
        $targetSweetId = $this->douceur->view();
        $this->assertEquals($this->getBrowserUrl() . Douceur::PATH_DOUCEUR_VIEW . $targetSweetId, $this->url());
        /** Trigger click event [DELETE] on sweet item */
        $this->assertTrue($this->douceur->deleteAction());
        /** Check if we're redirect on homepage */
        $this->assertEquals($this->getBrowserUrl(), $this->url());
        /** Check if items have been decrement by one lol */
        $this->assertEquals($this->douceur->countDouceursItems(), $currentDouceurItems - 1);
    }
}