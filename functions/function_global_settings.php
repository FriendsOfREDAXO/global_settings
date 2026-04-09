<?php

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::clangAdded() instead
 */
function rex_global_settings_clang_added(\rex_extension_point $ep): void
{
    \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::clangAdded($ep);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::clangDeleted() instead
 */
function rex_global_settings_clang_deleted(\rex_extension_point $ep): void
{
    \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::clangDeleted($ep);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::checkLangs() instead
 */
function rex_global_settings_check_langs(): void
{
    \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::checkLangs();
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::addFieldType() instead
 */
function rex_global_settings_add_field_type(string $label, string $dbtype, int $dblength): int|string
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::addFieldType($label, $dbtype, $dblength);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::deleteFieldType() instead
 */
function rex_global_settings_delete_field_type(int $field_type_id): bool|string
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::deleteFieldType($field_type_id);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::addField() instead
 */
function rex_global_settings_add_field(string $title, string $name, int $priority, string $attributes, int $type, string $default, ?string $params = null, ?string $validate = null, string $restrictions = ''): bool|string
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::addField($title, $name, $priority, $attributes, $type, $default, $params, $validate, $restrictions);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::deleteField() instead
 */
function rex_global_settings_delete_field(int|string $fieldIdOrName): bool|string
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::deleteField($fieldIdOrName);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::metaPrefix() instead
 */
function rex_global_settings_meta_prefix(string $name): string|false
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::metaPrefix($name);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::metaTable() instead
 */
function rex_global_settings_meta_table(string $prefix): string|false
{
    return \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::metaTable($prefix);
}

/**
 * @deprecated Use \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::extensionsHandler() instead
 */
function rex_global_settings_extensions_handler(\rex_extension_point $ep): void
{
    \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::extensionsHandler($ep);
}

