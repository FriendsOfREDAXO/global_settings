<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Rgbacolorpicker extends Input
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-control rex-global-settings-rgba-color-picker');
        $this->setAttribute('data-preferred-format', 'rgb');
        $this->setAttribute('data-show-alpha', 'true');
        $this->setAttribute('type', 'text');
    }

    public function getHtml()
    {
        $value = \rex_escape((string) $this->value);
        return '<input' . $this->getAttributeString() . ' value="' . $value . '" />';
    }
}

