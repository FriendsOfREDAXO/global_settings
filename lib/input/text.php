<?php

class rex_global_settings_input_text extends rex_global_settings_input
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('class', 'form-control');
        $this->setAttribute('type', 'text');
    }

    public function getHtml()
    {
        $value = htmlspecialchars($this->value);
        return '<input' . $this->getAttributeString() . ' value="' . $value . '" />';
    }
}
