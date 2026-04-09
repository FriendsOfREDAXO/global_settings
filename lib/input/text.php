<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Text extends Input
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-control');
        $this->setAttribute('type', 'text');
    }

    public function getHtml()
    {
        $value = \rex_escape((string) $this->value);
        return '<input' . $this->getAttributeString() . ' value="' . $value . '" />';
    }
}
