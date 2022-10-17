<?php

function getName(string $name): string {
    return rex_global_settings::FIELD_PREFIX . $name;
}

function getFieldID(string $name) {
    $sql = rex_sql::factory();
    $sql->setTable(rex::getTable('global_settings_type'));
    $sql->setWhere(['label' => 'text']);
    $sql->select('id');

    if ($sql->getRows() > 0) {
        return (int) $sql->getValue('id');
    }

    return null;
}

test('expect the field to be created', function ()
{
    $returnValue = rex_global_settings_add_field('test_title', getName('test_name'), 0, '', getFieldID('text'), '');
    expect($returnValue)->toBeTrue();
});

test('expect the field already exists', function ()
{
    $returnValue = rex_global_settings_add_field('test_title', getName('test_name'), 0, '', getFieldID('text'), '');
    expect($returnValue)->toBeString();
    expect($returnValue)->toEqual(rex_i18n::msg('global_settings_field_error_unique_name'));
});