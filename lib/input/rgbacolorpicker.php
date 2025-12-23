<?php

class rex_global_settings_input_rgbacolorpicker extends rex_global_settings_input
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
        $value = htmlspecialchars($this->value);
        return '<input' . $this->getAttributeString() . ' value="' . $value . '" />';
    }
}

