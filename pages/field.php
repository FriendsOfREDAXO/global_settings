<?php

rex_extension::register('REX_FORM_SAVED', function(rex_extension_point $ep) {
	rex_extension::registerPoint(new rex_extension_point('GLOBAL_SETTINGS_CHANGED'));

	return true;
});

rex_extension::register('REX_FORM_DELETED', function(rex_extension_point $ep) {
	rex_extension::registerPoint(new rex_extension_point('GLOBAL_SETTINGS_CHANGED'));

	return true;
});


$title = '';
$content = '';

//------------------------------> Parameter
if (empty($prefix)) {
    throw new rex_exception('Fehler: Prefix nicht definiert!');
}

if (empty($metaTable)) {
    throw new rex_exception('Fehler: metaTable nicht definiert!');
}

$Basedir = __DIR__;
$field_id = rex_request('field_id', 'int');

//------------------------------> Feld loeschen
if ($func == 'delete') {
    $field_id = rex_request('field_id', 'int', 0);
    if ($field_id != 0) {
        if (rex_global_settings_delete_field($field_id)) {
			rex_extension::registerPoint(new rex_extension_point('GLOBAL_SETTINGS_CHANGED'));

            echo rex_view::success(rex_i18n::msg('global_settings_field_successfull_deleted'));
        } else {
            echo rex_view::error(rex_i18n::msg('global_settings_field_error_deleted'));
        }
    }
    $func = '';
}

//------------------------------> Eintragsliste
if ($func == '') {
    echo rex_api_function::getMessage();

    $title = rex_i18n::msg('global_settings_field_list_caption');

    // replace LIKE wildcards
    $likePrefix = str_replace(['_', '%'], ['\_', '\%'], $prefix);

    $list = rex_global_settings_list::factory('SELECT id, name FROM ' . rex::getTablePrefix() . 'global_settings_field WHERE `name` LIKE "' . $likePrefix . '%" ORDER BY priority');
    $list->addTableAttribute('class', 'table-striped');

    $tdIcon = '<i class="rex-icon rex-icon-metainfo"></i>';
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '"><i class="rex-icon rex-icon-add-metainfo"></i></a>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'field_id' => '###id###']);

    $list->removeColumn('id');

    $list->setColumnLabel('id', rex_i18n::msg('global_settings_field_label_id'));
    $list->setColumnLayout('id', ['<th class="rex-table-id">###VALUE###</th>', '<td class="rex-table-id" data-title="' . rex_i18n::msg('global_settings_field_label_id') . '">###VALUE###</td>']);

    $list->setColumnLabel('name', rex_i18n::msg('global_settings_field_label_name'));
    $list->setColumnLayout('name', ['<th>###VALUE###</th>', '<td>###VALUE###</td>']);
    $list->setColumnParams('name', ['func' => 'edit', 'field_id' => '###id###']);

    $list->addColumn(rex_i18n::msg('global_settings_field_label_functions'), '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
    $list->setColumnLayout(rex_i18n::msg('global_settings_field_label_functions'), ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams(rex_i18n::msg('global_settings_field_label_functions'), ['func' => 'edit', 'field_id' => '###id###']);
    $list->addLinkAttribute(rex_i18n::msg('global_settings_field_label_functions'), 'class', 'rex-edit');

    $list->addColumn('delete', '<i class="rex-icon rex-icon-delete"></i> ' . rex_i18n::msg('delete'));
    $list->setColumnLayout('delete', ['', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams('delete', ['func' => 'delete', 'field_id' => '###id###']);
    $list->addLinkAttribute('delete', 'data-confirm', rex_i18n::msg('delete') . ' ?');
    $list->addLinkAttribute('delete', 'class', 'rex-delete');

    $list->setNoRowsMessage(rex_i18n::msg('global_settings_global_settings_not_found'));

    $content .= $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', $title);

    if (in_array($prefix, ['art_', 'med_'])) {
        $defaultFields = sprintf(
            '<div class="btn-group btn-group-xs"><a href="%s" class="btn btn-default">%s</a></div>',
            rex_url::currentBackendPage(['rex-api-call' => 'global_settings_default_fields_create', 'type' => $subpage]),
            rex_i18n::msg('global_settings_default_fields_create')
        );
        $fragment->setVar('options', $defaultFields, false);
    }

    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');
}
//------------------------------> Formular
elseif ($func == 'edit' || $func == 'add') {
    $title = rex_i18n::msg('global_settings_field_fieldset');
    $form = new rex_global_settings_table_expander($prefix, $metaTable, rex::getTablePrefix().'global_settings_field', 'id='.$field_id);

    if ($func == 'edit') {
        $form->addParam('field_id', $field_id);
    }

    $content .= $form->get();

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $title);
    $fragment->setVar('body', $content, false);
    $content = $fragment->parse('core/page/section.php');
}

echo $content;
