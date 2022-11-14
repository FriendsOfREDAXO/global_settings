<?php
$addon = rex_addon::get('global_settings');

rex_sql_table::get(rex::getTable('global_settings_field'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('title', 'varchar(255)', true, NULL))
    ->ensureColumn(new rex_sql_column('name', 'varchar(255)', true, NULL))
    ->ensureColumn(new rex_sql_column('notice', 'text', true, NULL))
    ->ensureColumn(new rex_sql_column('priority', 'int(10) unsigned'))
    ->ensureColumn(new rex_sql_column('attributes', 'text'))
    ->ensureColumn(new rex_sql_column('type_id', 'int(10) unsigned', true, NULL))
    ->ensureColumn(new rex_sql_column('default', 'varchar(255)'))
    ->ensureColumn(new rex_sql_column('params', 'text', true, NULL))
    ->ensureColumn(new rex_sql_column('validate', 'text', true, NULL))
    ->ensureColumn(new rex_sql_column('callback', 'text', true, NULL))
    ->ensureColumn(new rex_sql_column('restrictions', 'text', true, NULL))
    ->ensureColumn(new rex_sql_column('createuser', 'varchar(255)'))
    ->ensureColumn(new rex_sql_column('createdate', 'datetime'))
    ->ensureColumn(new rex_sql_column('updateuser', 'varchar(255)'))
    ->ensureColumn(new rex_sql_column('updatedate', 'datetime'))
    ->ensureIndex(new rex_sql_index('name', ['name'], rex_sql_index::UNIQUE))
    ->ensure();

//update existing textarea fields from text to mediumtext
if (rex_string::versionCompare($addon->getVersion(), '2.7.1', '<=')) {
    $sql = rex_sql::factory();
    $sql->prepareQuery('SELECT name FROM ' . rex::getTable('global_settings_field') . ' WHERE type_id =:type_id');
    $sql->execute(['type_id' => '2']);
    $results = $sql->getArray();
    if($results) {
        foreach ($results as $result) {
            rex_sql_table::get(rex::getTable('global_settings'))->ensureColumn(new rex_sql_column($result['name'], 'mediumtext', true, NULL))->ensure();
        }
    }
}
