<div id="rexx-setup">
<?php

$content = '<h2>' . rex_i18n::rawMsg('xcore_setup_msg1') . '</h2>';
$content .= '<ul><li>' . rex_i18n::rawMsg('xcore_setup_msg1_desc1') . '</li><li>' . rex_i18n::rawMsg('xcore_setup_msg1_desc2') . '</li><li>' . rex_i18n::rawMsg('xcore_setup_msg1_desc3') . '</li></ul>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::rawMsg('xcore_setup_step1'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');



$rexxContent = file_get_contents($this->getPath('install/_htaccess'));
$content = '<h2>' . rex_i18n::rawMsg('xcore_setup_msg2') . '</h2>';
$content .= '<ul><li>' . rex_i18n::rawMsg('xcore_setup_msg2_desc1') . '</li><li>' . rex_i18n::rawMsg('xcore_setup_msg2_desc2') . '</li></ul>';
$content .= '<div class="rexx-code"><code><pre>' . highlight_string($rexxContent, true)  . '</pre></code></div>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::rawMsg('xcore_setup_step2'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');



$rexxContent = file_get_contents($this->getPath('code/boilerplate/template.php'));
$content = '<h2>' . rex_i18n::rawMsg('xcore_setup_msg3') . '</h2>';
$content .= '<ul><li>' . rex_i18n::rawMsg('xcore_setup_msg3_desc') . '</li></ul>';
$content .= '<div class="rexx-code"><code><pre>' . highlight_string($rexxContent, true)  . '</pre></code></div>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::rawMsg('xcore_setup_step3'));
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
?>
</div>
