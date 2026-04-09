<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Linkbutton extends Input
{
    private $buttonId;
    private $categoryId;

    public function __construct()
    {
        parent::__construct();
        $this->buttonId = '';
        $this->categoryId = '';
    }

    public function setButtonId($buttonId)
    {
        $this->buttonId = $buttonId;
        $this->setAttribute('id', 'LINK_' . $buttonId);
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getHtml()
    {
        $buttonId = $this->buttonId;
        $categoryId = $this->categoryId;
        $value = \rex_escape((string) $this->value);
        $name = $this->attributes['name'];

        $field = \rex_var_link::getWidget($buttonId, $name, $value, ['category' => $categoryId]);

        return $field;
    }
}
