<?php

function rex_global_settings_clang_added($ep)
{
    $params = $ep->getParams();
    $newClangId = $params['clang']->getId();

    $sql = rex_sql::factory();
    $sql->setQuery('INSERT INTO `' . rex::getTablePrefix() . 'global_settings` (`clang`) VALUES (' . $newClangId . ')');
}

function rex_global_settings_clang_deleted($ep)
{
    $params = $ep->getParams();
    $id = $params['id'];

    $sql = rex_sql::factory();
    $sql->setQuery('DELETE FROM `' . rex::getTablePrefix() . 'global_settings` WHERE `clang` = ' . $id);
}

function rex_global_settings_check_langs()
{
    foreach (rex_clang::getAll() as $clang) {
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT `clang` FROM ' . rex::getTablePrefix() . 'global_settings WHERE `clang` = ' . $clang->getId());

        switch ($sql->getRows()) {
            case 0:
                $sql = rex_sql::factory();
                $sql->setQuery('INSERT INTO `' . rex::getTablePrefix() . 'global_settings` (`clang`) VALUES (' . $clang->getId() . ')');
                break;
            case 1:
                // clang is in the database
                break;
            default:
                throw new Exception('global_settings: clang #' . $clang->getId() . ' is ' . $sql->getRows() . 'x in the database, only once allowed.');
        }
    }
}

/**
 * Fügt einen neuen Feldtyp ein.
 *
 * Gibt beim Erfolg die Id des Feldes zurück, bei Fehler die Fehlermeldung
 */
function rex_global_settings_add_field_type($label, $dbtype, $dblength)
{
    if (!is_string($label) || empty($label)) {
        return rex_i18n::msg('global_settings_field_error_invalid_name');
    }

    if (!is_string($dbtype) || empty($dbtype)) {
        return rex_i18n::msg('global_settings_field_error_invalid_type');
    }

    if (!is_int($dblength) || empty($dblength)) {
        return rex_i18n::msg('global_settings_field_error_invalid_length');
    }

    $qry = 'SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_type WHERE label=:label LIMIT 1';
    $sql = rex_sql::factory();
    $sql->setQuery($qry, [':label' => $label]);
    if ($sql->getRows() != 0) {
        return rex_i18n::msg('global_settings_field_error_unique_type');
    }

    $sql->setTable(rex::getTablePrefix() . 'global_settings_type');
    $sql->setValue('label', $label);
    $sql->setValue('dbtype', $dbtype);
    $sql->setValue('dblength', $dblength);

    $sql->insert();
    return $sql->getLastId();
}

/**
 * Löscht einen Feldtyp.
 *
 * Gibt beim Erfolg true zurück, sonst eine Fehlermeldung
 */
function rex_global_settings_delete_field_type($field_type_id)
{
    if (!is_int($field_type_id) || empty($field_type_id)) {
        return rex_i18n::msg('global_settings_field_error_invalid_typeid');
    }

    $sql = rex_sql::factory();
    $sql->setTable(rex::getTablePrefix() . 'global_settings_type');
    $sql->setWhere(['id' => $field_type_id]);

    $sql->delete();
    return $sql->getRows() == 1;
}

/**
 * Fügt ein MetaFeld hinzu und legt dafür eine Spalte in der MetaTable an.
 */
function rex_global_settings_add_field($title, $name, $priority, $attributes, $type, $default, $params = null, $validate = null, $restrictions = '')
{
    $prefix = rex_global_settings_meta_prefix($name);
    $metaTable = rex_global_settings_meta_table($prefix);

    // Prefix korrekt?
    if (!$metaTable) {
        return rex_i18n::msg('global_settings_field_error_invalid_prefix');
    }

    // TypeId korrekt?
    $qry = 'SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_type WHERE id=' . $type . ' LIMIT 2';
    $sql = rex_sql::factory();
    $typeInfos = $sql->getArray($qry);

    if ($sql->getRows() != 1) {
        return rex_i18n::msg('global_settings_field_error_invalid_type');
    }

    $fieldDbType = $typeInfos[0]['dbtype'];
    $fieldDbLength = $typeInfos[0]['dblength'];

    // Spalte existiert schon?
    $sql->setQuery('SELECT * FROM ' . $metaTable . ' LIMIT 1');
    if (in_array($name, $sql->getFieldnames())) {
        return rex_i18n::msg('global_settings_field_error_unique_name');
    }

    // Spalte extiert laut global_settings_field?
    $qry = 'SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_field WHERE name=:name LIMIT 1';
    $sql = rex_sql::factory();
    $sql->setQuery($qry, [':name' => $name]);
    if ($sql->getRows() != 0) {
        return rex_i18n::msg('global_settings_field_error_unique_name');
    }

    $sql->setTable(rex::getTablePrefix() . 'global_settings_field');
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
    $prefix = str_replace(['_', '%'], ['\_', '\%'], $prefix);

    rex_sql_util::organizePriorities(rex::getTablePrefix() . 'global_settings_field', 'priority', 'name LIKE "' . $prefix . '%"', 'priority, updatedate');

    $tableManager = new rex_global_settings_table_manager($metaTable);
    return $tableManager->addColumn($name, $fieldDbType, $fieldDbLength, $default);
}

function rex_global_settings_delete_field($fieldIdOrName)
{
    // Löschen anhand der FieldId
    if (is_int($fieldIdOrName)) {
        $fieldQry = 'SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_field WHERE id=:idOrName LIMIT 2';
        $invalidField = rex_i18n::msg('global_settings_field_error_invalid_fieldid');
    } // Löschen anhand des Feldnames
    elseif (is_string($fieldIdOrName)) {
        $fieldQry = 'SELECT * FROM ' . rex::getTablePrefix() . 'global_settings_field WHERE name=:idOrName LIMIT 2';
        $invalidField = rex_i18n::msg('global_settings_field_error_invalid_name');
    } else {
        throw new InvalidArgumentException('global_settings: Unexpected type for $fieldIdOrName!');
    }
    // Feld existiert?
    $sql = rex_sql::factory();
    $sql->setQuery($fieldQry, [':idOrName' => $fieldIdOrName]);

    if ($sql->getRows() != 1) {
        return $invalidField;
    }

    $name = $sql->getValue('name');
    $field_id = $sql->getValue('id');

    $prefix = rex_global_settings_meta_prefix($name);
    $metaTable = rex_global_settings_meta_table($prefix);

    // Spalte existiert?
    $sql->setQuery('SELECT * FROM ' . $metaTable . ' LIMIT 1');
    if (!in_array($name, $sql->getFieldnames())) {
        return rex_i18n::msg('global_settings_field_error_invalid_name');
    }

    $sql->setTable(rex::getTablePrefix() . 'global_settings_field');
    $sql->setWhere(['id' => $field_id]);

    $sql->delete();

    $tableManager = new rex_global_settings_table_manager($metaTable);
    return $tableManager->deleteColumn($name);
}

/**
 * Extrahiert den Prefix aus dem Namen eine Spalte.
 */
function rex_global_settings_meta_prefix($name)
{
    if (!is_string($name)) {
        return false;
    }

    if (($pos = strpos($name, '_')) !== false) {
        return substr(strtolower($name), 0, $pos + 1);
    }

    return false;
}

/**
 * Gibt die mit dem Prefix verbundenen Tabellennamen zurück.
 */
function rex_global_settings_meta_table($prefix)
{
    $metaTables = rex_addon::get('global_settings')->getProperty('metaTables', []);

    if (isset($metaTables[$prefix])) {
        return $metaTables[$prefix];
    }

    return false;
}

/**
 * Bindet ggf extensions ein.
 *
 * @param rex_extension_point $ep
 */
function rex_global_settings_extensions_handler(rex_extension_point $ep)
{
    $page = $ep->getSubject();
    $mainpage = rex_be_controller::getCurrentPagePart(1);
    $mypage = 'global_settings';

    // additional javascripts
    if ($mainpage == 'global_settings') {
        rex_view::addJsFile(rex_url::addonAssets($mypage, 'js/spectrum.js'));
        rex_view::addJsFile(rex_url::addonAssets($mypage, 'js/global_settings.js'));

        rex_view::addCssFile(rex_url::addonAssets($mypage, 'css/spectrum.css'));
        rex_view::addCssFile(rex_url::addonAssets($mypage, 'css/global_settings.css'));
    }
}
