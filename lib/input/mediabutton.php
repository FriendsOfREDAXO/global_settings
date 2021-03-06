<?php

class rex_global_settings_input_mediabutton extends rex_global_settings_input
{
    private $buttonId;
    private $args = [];

    public function __construct()
    {
        parent::__construct();
        $this->buttonId = '';
    }

    public function setButtonId($buttonId)
    {
        $this->buttonId = $buttonId;
        $this->setAttribute('id', 'REX_MEDIA_' . $buttonId);
    }

    public function setCategoryId($categoryId)
    {
        $this->args['category'] = $categoryId;
    }

    public function setTypes($types)
    {
        $this->args['types'] = $types;
    }

    public function setPreview($preview = true)
    {
        $this->args['preview'] = $preview;
    }

    public function getHtml()
    {
        $buttonId = $this->buttonId;
        $value = htmlspecialchars($this->value);
        $name = $this->attributes['name'];
        $args = $this->args;

        $field = rex_var_media::getWidget($buttonId, $name, $value, $args);

        return $field;
    }
}
