<?php
$file = rex_file::get($this->getPath('CHANGELOG.md'));

$parsedown = new rex_global_settings_parsedown();
$content = $parsedown->text($file);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('help_changelog'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
