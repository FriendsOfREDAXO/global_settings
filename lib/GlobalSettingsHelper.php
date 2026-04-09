<?php

namespace FriendsOfRedaxo\GlobalSettings;


class GlobalSettingsHelper
{
    public static function isMediaInUse(\rex_extension_point $ep)
    {
        $params = $ep->getParams();
        $warning = $ep->getSubject();
        $fileName = \rex_string::sanitizeHtml($params['filename']);

        $sql = \rex_sql::factory();
        $sql->setQuery('SELECT `name` FROM `' . \rex::getTablePrefix() . 'global_settings_field` WHERE `type_id` IN(6,7)');
        $rows = $sql->getRows();

        /**
         * get column names.
         */
        $in = [];
        for ($i = 0; $i < $rows; ++$i) {
            $name = $sql->getValue('name');
            $in[] = $name;
            $sql->next();
        }

        if (!empty($in)) {
            $sql = \rex_sql::factory();
            $sql->setQuery('SELECT * FROM `' . \rex::getTablePrefix() . 'global_settings` WHERE  "' . $fileName . '" IN(' . implode(',', $in) . ')');
            $rows = $sql->getRows();
            $columns = $sql->getArray();
        }

        /**
         * if filename does not exist.
         */
        if (0 == $rows) {
            return $warning;
        }

        /**
         * get warnings.
         */
        $messages = '';
        foreach ($columns[0] as $key => $val) {
            if (str_contains($val, $fileName)) {
                $sql = \rex_sql::factory();
                $sql->setQuery('SELECT * FROM `' . \rex::getTablePrefix() . 'global_settings_field` WHERE `name` = "' . $key . '"');

                $messages .= '<li><a href="javascript:openPage(\'' . \rex_url::backendPage('global_settings/settings') . '\')">' . \rex_i18n::msg('global_settings_title') . ': ' . $sql->getValue('title') . '</a></li>';
            }
        }

        if ('' !== $messages) {
            $warning[] = '<ul>' . $messages . '</ul>';
        }

        return $warning;
    }

    public static function clangAdded(\rex_extension_point $ep): void
    {
        $params = $ep->getParams();
        $newClangId = (int) $params['clang']->getId();
    
        self::checkLangs();
    }

    public static function clangDeleted(\rex_extension_point $ep): void
    {
        $params = $ep->getParams();
        $id = (int) $params['id'];
    
        $sql = \rex_sql::factory();
        $sql->setQuery('DELETE FROM ' . \rex::getTablePrefix() . 'global_settings WHERE clang = ?', [$id]);
    }

    public static function checkLangs(): void
    {
        $domains = [];
        if (\rex_addon::get('yrewrite')->isAvailable()) {
            foreach (\rex_yrewrite::getDomains() as $d) {
                $domains[] = (int) $d->getId();
            }
        }
        if (empty($domains)) {
            $domains = [1];
        }
    
        foreach (\rex_clang::getAllIds() as $clangId) {
            foreach ($domains as $domainId) {
                try {
                    $sql = \rex_sql::factory();
                    $sql->setQuery('SELECT clang FROM ' . \rex::getTablePrefix() . 'global_settings WHERE clang = ? AND domain_id = ?', [$clangId, $domainId]);
        
                    if ($sql->getRows() === 0) {
                        $insert = \rex_sql::factory();
                        $insert->setQuery('INSERT INTO ' . \rex::getTablePrefix() . 'global_settings (clang, domain_id) VALUES (?, ?)', [$clangId, $domainId]);
                    }
                } catch (\rex_sql_exception $e) {
                    // Ignore exception during update/reinstall before schema is migrated
                }
            }
        }
    }

    /**
     * Fügt einen neuen Feldtyp ein.
     *
     * Gibt beim Erfolg die Id des Feldes zurück, bei Fehler die Fehlermeldung
     */
    public static function addFieldType(string $label, string $dbtype, int $dblength): int|string
    {
        if (empty($label)) {
            return \rex_i18n::msg('global_settings_field_error_invalid_name');
        }
    
        if (empty($dbtype)) {
            return \rex_i18n::msg('global_settings_field_error_invalid_type');
        }
    
        if (empty($dblength)) {
            return \rex_i18n::msg('global_settings_field_error_invalid_length');
        }
    
        $qry = 'SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_type WHERE label=:label LIMIT 1';
        $sql = \rex_sql::factory();
        $sql->setQuery($qry, [':label' => $label]);
        if (0 != $sql->getRows()) {
            return \rex_i18n::msg('global_settings_field_error_unique_type');
        }
    
        $sql->setTable(\rex::getTablePrefix() . 'global_settings_type');
        $sql->setValue('label', $label);
        $sql->setValue('dbtype', $dbtype);
        $sql->setValue('dblength', $dblength);
    
        $sql->insert();
        return (int) $sql->getLastId();
    }

    /**
     * Löscht einen Feldtyp.
     *
     * Gibt beim Erfolg true zurück, sonst eine Fehlermeldung
     */
    public static function deleteFieldType(int $field_type_id): bool|string
    {
        if (empty($field_type_id)) {
            return \rex_i18n::msg('global_settings_field_error_invalid_typeid');
        }
    
        $sql = \rex_sql::factory();
        $sql->setTable(\rex::getTablePrefix() . 'global_settings_type');
        $sql->setWhere(['id' => $field_type_id]);
    
        $sql->delete();
        return 1 == $sql->getRows();
    }

    /**
     * Fügt ein MetaFeld hinzu und legt dafür eine Spalte in der MetaTable an.
     */
    public static function addField(string $title, string $name, int $priority, string $attributes, int $type, string $default, ?string $params = null, ?string $validate = null, string $restrictions = ''): bool|string
    {
        $prefix = self::metaPrefix($name);
        if (!$prefix) {
            return \rex_i18n::msg('global_settings_field_error_invalid_prefix');
        }
        
        $metaTable = self::metaTable($prefix);
    
        // Prefix korrekt?
        if (!$metaTable) {
            return \rex_i18n::msg('global_settings_field_error_invalid_prefix');
        }
    
        // TypeId korrekt?
        $qry = 'SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_type WHERE id=? LIMIT 2';
        $sql = \rex_sql::factory();
        $typeInfos = $sql->getArray($qry, [$type]);
    
        if (1 != count($typeInfos)) {
            return \rex_i18n::msg('global_settings_field_error_invalid_type');
        }
    
        $fieldDbType = $typeInfos[0]['dbtype'];
        $fieldDbLength = $typeInfos[0]['dblength'];
    
        // Spalte existiert schon?
        $sql->setQuery('SELECT * FROM ' . $sql->escapeIdentifier($metaTable) . ' LIMIT 1');
        if (in_array($name, $sql->getFieldnames())) {
            return \rex_i18n::msg('global_settings_field_error_unique_name');
        }
    
        // Spalte extiert laut global_settings_field?
        $qry = 'SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_field WHERE name=:name LIMIT 1';
        $sql = \rex_sql::factory();
        $sql->setQuery($qry, [':name' => $name]);
        if (0 != $sql->getRows()) {
            return \rex_i18n::msg('global_settings_field_error_unique_name');
        }
    
        $sql->setTable(\rex::getTablePrefix() . 'global_settings_field');
        $sql->setValue('title', $title);
        $sql->setValue('name', $name);
        $sql->setValue('priority', $priority);
        $sql->setValue('attributes', $attributes);
        $sql->setValue('type_id', $type);
        $sql->setValue('default', $default);
        $sql->setValue('params', $params);
        $sql->setValue('validate', $validate);
        $sql->setValue('restrictions', $restrictions);
        $sql->addGlobalUpdateFields();
        $sql->addGlobalCreateFields();
    
        $sql->insert();
    
        // replace LIKE wildcards
        $prefixLike = str_replace(['_', '%'], ['\_', '\%'], $prefix);
    
        \rex_sql_util::organizePriorities(\rex::getTablePrefix() . 'global_settings_field', 'priority', 'name LIKE "' . $prefixLike . '%"', 'priority, updatedate');
    
        $tableManager = new \FriendsOfRedaxo\GlobalSettings\TableManager($metaTable);
        return $tableManager->addColumn($name, $fieldDbType, $fieldDbLength, $default);
    }

    public static function deleteField(int|string $fieldIdOrName): bool|string
    {
        // Löschen anhand der FieldId
        if (is_int($fieldIdOrName)) {
            $fieldQry = 'SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_field WHERE id=:idOrName LIMIT 2';
            $invalidField = \rex_i18n::msg('global_settings_field_error_invalid_fieldid');
        } // Löschen anhand des Feldnames
        else {
            $fieldQry = 'SELECT * FROM ' . \rex::getTablePrefix() . 'global_settings_field WHERE name=:idOrName LIMIT 2';
            $invalidField = \rex_i18n::msg('global_settings_field_error_invalid_name');
        }
    
        // Feld existiert?
        $sql = \rex_sql::factory();
        $sql->setQuery($fieldQry, [':idOrName' => $fieldIdOrName]);
    
        if (1 != $sql->getRows()) {
            return $invalidField;
        }
    
        $name = (string) $sql->getValue('name');
        $field_id = (int) $sql->getValue('id');
    
        $prefix = self::metaPrefix($name);
        if (!$prefix) {
            return \rex_i18n::msg('global_settings_field_error_invalid_prefix');
        }
        
        $metaTable = self::metaTable($prefix);
        if (!$metaTable) {
            return \rex_i18n::msg('global_settings_field_error_invalid_prefix');
        }
    
        // Spalte existiert?
        $sql->setQuery('SELECT * FROM ' . $sql->escapeIdentifier($metaTable) . ' LIMIT 1');
        if (!in_array($name, $sql->getFieldnames())) {
            return \rex_i18n::msg('global_settings_field_error_invalid_name');
        }
    
        $sql->setTable(\rex::getTablePrefix() . 'global_settings_field');
        $sql->setWhere(['id' => $field_id]);
    
        $sql->delete();
    
        $tableManager = new \FriendsOfRedaxo\GlobalSettings\TableManager($metaTable);
        return $tableManager->deleteColumn($name);
    }

    /**
     * Extrahiert den Prefix aus dem Namen eine Spalte.
     */
    public static function metaPrefix(string $name): string|false
    {
        if (($pos = strpos($name, '_')) !== false) {
            return substr(strtolower($name), 0, $pos + 1);
        }
    
        return false;
    }

    /**
     * Gibt die mit dem Prefix verbundenen Tabellennamen zurück.
     */
    public static function metaTable(string $prefix): string|false
    {
        $metaTables = \rex_addon::get('global_settings')->getProperty('metaTables', []);
    
        if (isset($metaTables[$prefix])) {
            return $metaTables[$prefix];
        }
    
        return false;
    }

    /**
     * Bindet ggf extensions ein.
     */
    public static function extensionsHandler(\rex_extension_point $ep): void
    {
        $mainpage = \rex_be_controller::getCurrentPagePart(1);
        $mypage = 'global_settings';
    
        // additional javascripts
        if ($mypage === $mainpage) {
            \rex_view::addJsFile(\rex_url::addonAssets($mypage, 'js/spectrum.js'));
            \rex_view::addJsFile(\rex_url::addonAssets($mypage, 'js/global_settings.js'));
    
            \rex_view::addCssFile(\rex_url::addonAssets($mypage, 'css/spectrum.css'));
            \rex_view::addCssFile(\rex_url::addonAssets($mypage, 'css/global_settings.css'));
        }
    }

}
