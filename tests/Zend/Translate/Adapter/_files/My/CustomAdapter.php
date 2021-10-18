<?php

class My_CustomAdapter extends Zend_Translate_Adapter {
    protected function _loadTranslationData($data, $locale, array $options = array())
    {
        return array($locale => $data);
    }

    public function toString()
    {
        return "My_CustomAdapter";
    }
}
