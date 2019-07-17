<?php
$file = rex_file::get($this->getPath('README.md'));
$file = str_replace('(docs/rexx_api.md)', '(' . rex_url::backendPage('xcore/rexx_api') . ')', $file);

$parsedown = new rexx_markdown();
$content = $parsedown->text($file);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('help_readme'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
