<?php

// Parameter
$Basedir = __DIR__;

$subpage = rex_be_controller::getCurrentPagePart(2);
$func = rex_request('func', 'string');
$prefix = '';

echo rex_view::title(rex_i18n::msg('global_settings_title'));

// Include Current Page
switch ($subpage) {
    case 'fields':
        $prefix = 'glob_';
        break;
    default:
        $prefix = '';
        break;
}

if ('fields' == $subpage) {
    $metaTable = rex_global_settings_meta_table($prefix);
    require $Basedir . '/field.php';
} elseif ('help' == $subpage) {
    require $Basedir . '/help.' . rex_be_controller::getCurrentPagePart(3) . '.php';
} elseif ('settings' == $subpage) {
    require $Basedir . '/settings.php';
}
