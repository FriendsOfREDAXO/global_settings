<?php
[$Toc, $Content] = rex_markdown::factory()->parseWithToc(rex_file::require($this->getPath('LICENSE.md')), 2, 3, false);

$fragment = new rex_fragment();
$fragment->setVar('content', $Content, false);
$fragment->setVar('toc', $Toc, false);
$content = $fragment->parse('core/page/docs.php');

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('help_license'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
