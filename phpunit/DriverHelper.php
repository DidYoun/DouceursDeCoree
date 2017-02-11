<?php

/**
 * Class ${NAME}
 *
 * @author              Didier Youn <didier.youn@gmail.com>
 * @copyright           Copyright (c) 2016 DidYoun
 * @license             http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link                http://www.didier-youn.com
 */
require_once __DIR__ . '/../vendor/autoload.php';

class DriverHelper extends PHPUnit_Extensions_Selenium2TestCase
{
    /** @var string DEFAULT_PATTERN_TITLE */
    const DEFAULT_PATTERN_TITLE = "Douceurs de Corée | ";
    /** @var string DEFAULT_TITLE_NOT_FOUND */
    const DEFAULT_TITLE_NOT_FOUND = "Page Not Found";
    /**  @var string PATH_DOUCEUR_VIEW */
    const PATH_DOUCEUR_VIEW = "douceur/";
    /**  @var string PATH_DOUCEUR_CREATE */
    const PATH_DOUCEUR_CREATE = "douceur/new";
    /**  @var string PATH_DOUCEUR_EDIT */
    const PATH_DOUCEUR_EDIT = "douceur/edit/";
    /**  @var string PATH_DOUCEUR_DELETE */
    const PATH_DOUCEUR_DELETE = "douceur/delete/";

    /**
     * Render page create view
     *
     * @return bool
     */
    protected function renderDouceurCreateView()
    {
        $this->url('/');
        $btnCreateNewSweet = $this->byId('btn-create');
        $btnCreateNewSweet->click();
        if ($this->getBrowserUrl() . self::PATH_DOUCEUR_CREATE == $this->url()) {
            return true;
        }
        return false;
    }

    /**
     * Count current items in homepage
     *
     * @return int
     */
    protected function countDouceursItems()
    {
        $this->url('/');
        /** @var array $items */
        $items = $this->elements($this->using('css selector')->value('*[class="douceur-item"]'));
        if (!isset($items) || !is_array($items)) {
            return 0;
        }

        return sizeof($items);
    }

    /**
     * Get random identifier of sweet
     *
     * @return bool
     */
    protected function getRandomIdentifierFromSweetItems()
    {
        /** @var array $items */
        $items = $this->elements($this->using('css selector')->value('*[class="douceur-item"]'));
        if (!isset($items) || !is_array($items) || sizeof($items) < 1) {
            return false;
        }
        /** @var int $randomKey */
        $randomKey = array_rand($items, 1);
        /** @var string $itemId */
        $itemId = $items[$randomKey]->attribute('id');
        preg_match_all('/_(\d*)$/', $itemId, $matches);
        if (!isset($matches) || !isset($matches[1]) || !isset($matches[1][0])) {
            return false;
        }

        return $matches[1][0];
    }

    /**
     * Clear inputs fields
     *
     * @param array|null $attributes
     * @return bool
     */
    protected function resetInputValues(array $attributes = null)
    {
        /** @var string $attribute */
        foreach ($attributes as $attribute) {
            /** @var array $element */
            $element = $this->byName($attribute);
            if (!isset($element)) {
                continue;
            }
            $element->clear();
        }

        return true;
    }
}