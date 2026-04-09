<?php

// Parameter
$Basedir = __DIR__;

$subpage = rex_be_controller::getCurrentPagePart(2);
$func = rex_request('func', 'string');
$prefix = '';

$domainSelector = '';
if ('settings' == $subpage && rex_addon::get('yrewrite')->isAvailable()) {
    $domainId = rex_request('domain_id', 'int', rex_session('global_settings_domain_id', 'int', rex_yrewrite::getDefaultDomain()->getId()));
    
    $sel_domain = new rex_select();
    $sel_domain->setId('global_settings_domain_id');
    $sel_domain->setName('domain_id');
    $sel_domain->setSize(1);
    $sel_domain->setAttribute('class', 'form-control selectpicker');
    $sel_domain->setAttribute('data-live-search', 'true');
    $sel_domain->setAttribute('onchange', 'location.href=\''.rex_url::backendPage('global_settings/settings').'&domain_id=\'+this.value;');
    
    foreach (rex_yrewrite::getDomains() as $domain) {
        $sel_domain->addOption($domain->getName(), $domain->getId());
    }
    
    $sel_domain->setSelected($domainId);

    $domainSelector = '
        <div class="navbar-form navbar-left" style="margin-left: 0; padding-left: 15px; margin-right: 15px;">
            <div class="form-group">
                <label style="margin-right: 10px; font-weight: normal;"><i class="rex-icon rex-icon-globe"></i> Domain:</label>
                ' . $sel_domain->get() . '
            </div>
        </div>';
}

$titleOutput = rex_view::title(rex_i18n::msg('global_settings_title'));

if ($domainSelector) {
    if (preg_match('/(<nav class="navbar[^>]*>)\s*(<ul class="nav navbar-nav">)/i', $titleOutput)) {
        $titleOutput = preg_replace(
            '/(<nav class="navbar[^>]*>)\s*(<ul class="nav navbar-nav">)/i',
            '$1' . $domainSelector . '$2',
            $titleOutput,
            1
        );
    } else {
        // Fallback falls die Navigation anders aussieht
        $titleOutput .= '<nav class="navbar navbar-default">' . $domainSelector . '</nav>';
    }
}

echo $titleOutput;

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
    $metaTable = \FriendsOfRedaxo\GlobalSettings\GlobalSettingsHelper::metaTable($prefix);
    require $Basedir . '/field.php';
} elseif ('help' == $subpage) {
    require $Basedir . '/help.' . rex_be_controller::getCurrentPagePart(3) . '.php';
} elseif ('settings' == $subpage) {
    require $Basedir . '/settings.php';
}
