<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Colorpicker extends Input
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-control rex-global-settings-color-picker');
        $this->setAttribute('type', 'text');
    }

    public function getHtml()
    {
        $value = \rex_escape((string) $this->value);
        return '<input' . $this->getAttributeString() . ' value="' . $value . '" />';
    }
}
