<?php

$mypage = 'global_settings';

if (!defined('REX_GLOBAL_SETTINGS_FIELD_TEXT')) {
    // Feldtypen - now deprecated in favor of \FriendsOfRedaxo\GlobalSettings\TableManager::FIELD_*
    define('REX_GLOBAL_SETTINGS_FIELD_TEXT', 1);
    define('REX_GLOBAL_SETTINGS_FIELD_TEXTAREA', 2);
    define('REX_GLOBAL_SETTINGS_FIELD_SELECT', 3);
    define('REX_GLOBAL_SETTINGS_FIELD_RADIO', 4);
    define('REX_GLOBAL_SETTINGS_FIELD_CHECKBOX', 5);
    define('REX_GLOBAL_SETTINGS_FIELD_REX_MEDIA_WIDGET', 6);
    define('REX_GLOBAL_SETTINGS_FIELD_REX_MEDIALIST_WIDGET', 7);
    define('REX_GLOBAL_SETTINGS_FIELD_REX_LINK_WIDGET', 8);
    define('REX_GLOBAL_SETTINGS_FIELD_REX_LINKLIST_WIDGET', 9);
    define('REX_GLOBAL_SETTINGS_FIELD_DATE', 10);
    define('REX_GLOBAL_SETTINGS_FIELD_DATETIME', 11);
    define('REX_GLOBAL_SETTINGS_FIELD_LEGEND', 12);
    define('REX_GLOBAL_SETTINGS_FIELD_TIME', 13);
    define('REX_GLOBAL_SETTINGS_FIELD_COUNT', 13);
    define('REX_GLOBAL_SETTINGS_FIELD_TAB', 14);
    define('REX_GLOBAL_SETTINGS_FIELD_COLORPICKER', 15);
    define('REX_GLOBAL_SETTINGS_FIELD_RGBACOLORPICKER', 16);
}

$this->setProperty('prefixes', ['glob_']);
$this->setProperty('metaTables', [
    'glob_' => rex::getTablePrefix() . 'global_settings',
]);

rex_extension::register('PACKAGES_INCLUDED', 'rex_global_settings::init');

if (rex::isBackend()) {
    $curDir = __DIR__;
    require_once $curDir . '/functions/function_global_settings.php';

    \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::checkLangs();

    rex_extension::register('PAGE_CHECKED', ['\\FriendsOfRedaxo\\GlobalSettings\\GlobalSettingsHelper', 'extensionsHandler']);

    rex_extension::register('CLANG_ADDED', ['\\FriendsOfRedaxo\\GlobalSettings\\GlobalSettingsHelper', 'clangAdded']);
    rex_extension::register('CLANG_DELETED', ['\\FriendsOfRedaxo\\GlobalSettings\\GlobalSettingsHelper', 'clangDeleted']);

    rex_extension::register('PAGES_PREPARED', static function () {
        if (rex::getUser() instanceof rex_user) {
            if (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('global_settings[settings]')) {
                $page = rex_be_controller::getPageObject('global_settings/settings');

                if ($page && count(rex_clang::getAll(false)) > 1) {
                    $clang_id = str_replace('clang', '', (string) rex_be_controller::getCurrentPagePart(3));
                    $clangAll = rex_clang::getAll();

                    $domainId = rex_request('domain_id', 'int', rex_session('global_settings_domain_id', 'int', 1));
                    if (!$domainId && rex_addon::get('yrewrite')->isAvailable()) {
                        $domainId = rex_yrewrite::getDefaultDomain()->getId();
                    }

                    if (rex_addon::get('yrewrite')->isAvailable()) {
                        if ($domain = rex_yrewrite::getDomainById($domainId)) {
                            $allowedClangs = $domain->getClangs();
                            $clangAll = array_filter($clangAll, static function ($clang) use ($allowedClangs) {
                                return in_array($clang->getId(), $allowedClangs);
                            });
                        }
                    }

                    foreach ($clangAll as $id => $clang) {
                        if (rex::getUser()->getComplexPerm('clang')->hasPerm($id)) {
                            $page->addSubpage((new rex_be_page('clang' . $id, $clang->getName()))->setIsActive($id == $clang_id));
                        }
                    }
                }
            }
        }
    });

    rex_extension::register('CACHE_DELETED', static function () {
        rex_global_settings::deleteCache();
    });

    rex_extension::register('BACKUP_AFTER_DB_IMPORT', static function () {
        rex_global_settings::deleteCache();
    });

    rex_extension::register('GLOBAL_SETTINGS_CHANGED', static function () {
        rex_global_settings::deleteCache();
    });

    rex_extension::register('MEDIA_IS_IN_USE', ['\\FriendsOfRedaxo\\GlobalSettings\\GlobalSettingsHelper', 'isMediaInUse']);
}
