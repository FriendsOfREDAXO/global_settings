<?php

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

rex_sql_util::importDump($this->getPath('_install.sql'));

$tablePrefixes = ['global_settings' => ['glob_']];
$columns = ['global_settings' => []];
foreach ($tablePrefixes as $table => $prefixes) {
    foreach (rex_sql::showColumns(rex::getTable($table)) as $column) {
        $column = $column['name'];
        $prefix = substr($column, 0, 4);
        if (in_array(substr($column, 0, 4), $prefixes)) {
            $columns[$table][$column] = true;
        }
    }
}

$sql = rex_sql::factory();
$sql->setQuery('SELECT p.name, p.default, t.dbtype, t.dblength FROM ' . rex::getTable('global_settings_field') . ' p, ' . rex::getTable('global_settings_type') . ' t WHERE p.type_id = t.id');
$rows = $sql->getRows();
$managers = [
    'global_settings' => new rex_global_settings_table_manager(rex::getTable('global_settings'))
];
for ($i = 0; $i < $sql->getRows(); ++$i) {
    $column = $sql->getValue('name');
    if (substr($column, 0, 5) == 'glob_') {
        $table = 'global_settings';
    } else {
		$table = 'error';
	}

    if (isset($columns[$table][$column])) {
        $managers[$table]->editColumn($column, $column, $sql->getValue('dbtype'), $sql->getValue('dblength'), $sql->getValue('default'));
    } else {
        $managers[$table]->addColumn($column, $sql->getValue('dbtype'), $sql->getValue('dblength'), $sql->getValue('default'));
    }

    unset($columns[$table][$column]);
    $sql->next();
}

rex_global_settings::deleteCache();
