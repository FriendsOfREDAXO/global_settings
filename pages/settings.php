<?php

$content = '';
$domainId = rex_request('domain_id', 'int', rex_session('global_settings_domain_id', 'int', null));

if ($domainId === null && rex_addon::get('yrewrite')->isAvailable()) {
    $domainId = rex_yrewrite::getDefaultDomain()->getId();
} else if ($domainId === null) {
    $domainId = 1;
}

rex_set_session('global_settings_domain_id', $domainId);

if (rex_post('savemeta', 'boolean')) {
// ...
    rex_extension::registerPoint(new rex_extension_point('GLOBAL_SETTINGS_CHANGED'));

    $content = rex_view::success(rex_i18n::msg('global_settings_metadata_saved'));
}

$panel = '<input type="hidden" name="save" value="1" />';

$clangId = filter_var(rex_be_controller::getCurrentPagePart(3), FILTER_SANITIZE_NUMBER_INT);
if ($clangId < 1 || !rex_clang::exists($clangId)) {
    $clangId = rex_clang::getStartId();
}

$oldClangId = $clangId;
if (rex_addon::get('yrewrite')->isAvailable() && $domainId) {
    if ($domain = rex_yrewrite::getDomainById($domainId)) {
        $allowedClangs = $domain->getClangs();
        if (!empty($allowedClangs) && !in_array($clangId, $allowedClangs)) {
            $clangId = reset($allowedClangs);
        }
    }
}

if ($oldClangId !== $clangId && rex_request('domain_id', 'int')) {
    // redirect to apply new valid clang in url
    rex_response::sendRedirect(rex_url::backendPage('global_settings/settings/clang'.$clangId, ['domain_id' => $domainId], false));
}

rex_clang::setCurrentId($clangId);

$panel .= '<input type="hidden" name="domain_id" value="' . $domainId . '" />';

$global_settingsHandler = new \FriendsOfRedaxo\GlobalSettings\Handler\GlobalSettingsHandler();
$form = $global_settingsHandler->getForm([
    'clang' => $clangId,
    'domain_id' => $domainId,
]);

$panel .= $form;

$formElements = [];

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="savemeta"' . rex::getAccesskey(rex_i18n::msg('update_metadata'), 'save') . ' value="1">' . rex_i18n::msg('global_settings_save_settings') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('global_settings_settings'), false);
$fragment->setVar('body', $panel, false);
$fragment->setVar('buttons', $buttons, false);
$content .= $fragment->parse('core/page/section.php');

$action = 'index.php?page=global_settings/settings';

if (count(rex_clang::getAll()) > 1) {
    $action .= '/clang' . $clangId;
}

echo '
    <form action="' . $action . '" method="post" enctype="multipart/form-data">
        ' . $content . '
    </form>';
