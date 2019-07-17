<?php

class rex_global_settings_list extends rex_list {
    public function replaceVariables($value)
    {
		$value = parent::replaceVariables($value);

		return str_replace(rex_global_settings::FIELD_PREFIX, '', $value);
    }
}
