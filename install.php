<?php

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
