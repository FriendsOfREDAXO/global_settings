<?php
$data = rex_file::get($this->getPath('README.md'));
     [$toc, $content] = rex_markdown::factory()->parseWithToc($data);
        $fragment = new rex_fragment();
        $fragment->setVar('content', $content, false);
        $fragment->setVar('toc', $toc, false);
        $content = $fragment->parse('core/page/docs.php');


        $fragment = new rex_fragment();
        $fragment->setVar('title', $this->i18n('help_readme'),false);
        $fragment->setVar('body', $content, false);
        echo $fragment->parse('core/page/section.php');

