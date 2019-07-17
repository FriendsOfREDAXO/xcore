<?php
$code = file_get_contents($this->getPath('code/resource_includer/snippets.php'));
$content = '<div class="rexx-code"><code><pre>' . highlight_string($code, true)  . '</pre></code></div>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::rawMsg('xcore_code_snippets_resource_includer'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
?>
