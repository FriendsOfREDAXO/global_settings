<?php

namespace FriendsOfRedaxo\GlobalSettings;


class GlobalSettingsList extends \rex_list
{
    public function replaceVariables($value)
    {
        $value = parent::replaceVariables($value);

        return str_replace(\FriendsOfRedaxo\GlobalSettings\GlobalSettings::FIELD_PREFIX, '', $value);
    }
}
