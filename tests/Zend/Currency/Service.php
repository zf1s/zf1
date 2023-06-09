<?php
/**
 * @see Zend_Currency_CurrencyInterface
 */
// require_once 'Zend/Currency/CurrencyInterface.php';

#[AllowDynamicProperties]
class Zend_Currency_Service implements Zend_Currency_CurrencyInterface
{
    public function getRate($from, $to)
    {}
}
