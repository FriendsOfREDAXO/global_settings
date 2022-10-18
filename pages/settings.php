<?php
$content = '';

if (rex_post('savemeta', 'boolean')) {
    rex_extension::registerPoint(new rex_extension_point('GLOBAL_SETTINGS_CHANGED'));

    $content = rex_view::success(rex_i18n::msg('global_settings_metadata_saved'));
}

$panel = '<input type="hidden" name="save" value="1" />';

$clangId = filter_var(rex_be_controller::getCurrentPagePart(3), FILTER_SANITIZE_NUMBER_INT);

if ($clangId < 1) {
    $clangId = 1;
}

if (rex_clang::exists($clangId)) {
    rex_clang::setCurrentId($clangId);
}

$global_settingsHandler = new rex_global_settings_global_settings_handler();
$form = $global_settingsHandler->getForm([
    'clang' => $clangId
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
