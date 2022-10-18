<?php

class rex_global_settings_helper
{
    public static function isMediaInUse(\rex_extension_point $ep)
    {
        $params = $ep->getParams();
        $warning = $ep->getSubject();
        $fileName = rex_string::sanitizeHtml($params['filename']);

        $sql = rex_sql::factory();
        $sql->setQuery('SELECT `name` FROM `' . rex::getTablePrefix() . 'global_settings_field` WHERE `type_id` IN(6,7)');
        $rows = $sql->getRows();

        /**
         * get column names
         */
        $in = [];
        for ($i = 0; $i < $rows; ++$i) {
            $name = $sql->getValue('name');
            $in[] = $name;
            $sql->next();
        }

        if (!empty($in)) {
            $sql = rex_sql::factory();
            $sql->setQuery('SELECT * FROM `' . rex::getTablePrefix() . 'global_settings` WHERE  "' . $fileName . '" IN(' . join(',', $in) . ')');
            $rows = $sql->getRows();
            $columns = $sql->getArray();
        }

        /**
         * if filename does not exist
         */
        if (0 == $rows) {
            return $warning;
        }

        /**
         * get warnings
         */
        $messages = '';
        foreach ($columns[0] as $key => $val) {
            if (strpos($val, $fileName) !== FALSE) {
                $sql = rex_sql::factory();
                $sql->setQuery('SELECT * FROM `' . rex::getTablePrefix() . 'global_settings_field` WHERE `name` = "' . $key . '"');

                $messages .= '<li><a href="javascript:openPage(\'' . rex_url::backendPage("global_settings/settings") . '\')">' . rex_i18n::msg("global_settings_title") . ': ' . $sql->getValue("title") . '</a></li>';
            }
        }

        if ($messages !== '') {
            $warning[] = '<ul>' . $messages . '</ul>';
        }

        return $warning;
    }
}
