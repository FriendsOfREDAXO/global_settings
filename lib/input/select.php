<?php

class rex_global_settings_input_select extends rex_global_settings_input
{
    private $select;

    public function __construct()
    {
        parent::__construct();

        $this->select = new rex_select();
        $this->setAttribute('class', 'form-control');
    }

    public function setValue($value)
    {
        $this->select->setSelected($value);
        parent::setValue($value);
    }

    public function setAttribute($name, $value)
    {
        if ('name' == $name) {
            $this->select->setName($value);
        } elseif ('id' == $name) {
            $this->select->setId($value);
        } else {
            $this->select->setAttribute($name, $value);
        }

        parent::setAttribute($name, $value);
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function getHtml()
    {
        return $this->select->get();
    }
}
