<?php
class ErrorThrowingController extends Zend_Controller_Action
{
    public function indexAction()
    {
        throw new TypeError('controller action triggered a type error');
    }
}
