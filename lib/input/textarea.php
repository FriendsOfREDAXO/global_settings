<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Textarea extends Input
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-control');
        $this->setAttribute('cols', '50');
        $this->setAttribute('rows', '6');
    }

    public function getHtml()
    {
        $value = \rex_escape((string) $this->value);
        return '<textarea' . (string) $this->getAttributeString() . '>' . $value . '</textarea>';
    }
}
