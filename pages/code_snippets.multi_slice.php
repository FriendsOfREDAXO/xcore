<?php
$code1 = file_get_contents($this->getPath('code/multi_slice/input.php'));
$content1 = '<div class="rexx-code"><code><pre>' . highlight_string($code1, true)  . '</pre></code></div>';

$code2 = file_get_contents($this->getPath('code/multi_slice/output.php'));
$content2 = '<div class="rexx-code"><code><pre>' . highlight_string($code2, true)  . '</pre></code></div>';


$content = '<div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#input" aria-controls="input" role="tab" data-toggle="tab">' .  rex_i18n::msg('xcore_module_input') . '</a></li>
    <li role="presentation"><a href="#output" aria-controls="css" role="output" data-toggle="tab">' .  rex_i18n::msg('xcore_module_output') . '</a></li>
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="input">' . $content1 . '</div>
    <div role="tabpanel" class="tab-pane" id="output">' . $content2 . '</div>
  </div>
</div>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('xcore_code_snippets_multi_slice'), false);
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
