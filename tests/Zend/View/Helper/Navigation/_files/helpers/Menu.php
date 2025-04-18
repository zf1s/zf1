<?php

use Zf1s\Compat\Types;

class My_View_Helper_Navigation_Menu
    extends Zend_View_Helper_Navigation_HelperAbstract
{
    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     *
     * @param  Zend_Navigation_Container|null $container  [optional] container to
     *                                               operate on
     * @return My_View_Helper_Navigation_Menu        fluent interface,
     *                                               returns self
     */
    public function menu($container = null)
    {
        Types::isNullable('container', $container, 'Zend_Navigation_Container');

        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
    }
    
    /**
     * Renders menu
     *
     * Implements {@link Zend_View_Helper_Navigation_Helper::render()}.
     *
     * @param  Zend_Navigation_Container|null $container  [optional] container to
     *                                               render. Default is to
     *                                               render the container
     *                                               registered in the helper.
     * @return string                                helper output
     */
    public function render($container = null)
    {
        Types::isNullable('container', $container, 'Zend_Navigation_Container');

        return '<menu/>';
    }
}