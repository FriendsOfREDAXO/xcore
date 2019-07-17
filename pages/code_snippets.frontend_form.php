<?php
$code1 = file_get_contents($this->getPath('code/frontend_form/form.php'));
$content1 = '<div class="rexx-code"><code><pre>' . highlight_string($code1, true)  . '</pre></code></div>';

$code2 = file_get_contents($this->getPath('code/frontend_form/form.css'));
$content2 = '<div class="rexx-code"><code><pre>' . highlight_string($code2, true)  . '</pre></code></div>';

$code3 = file_get_contents($this->getPath('code/frontend_form/form_fields.php'));
$content3 = '<div class="rexx-code"><code><pre>' . highlight_string($code3, true)  . '</pre></code></div>';


$content = '<div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#php" aria-controls="php" role="tab" data-toggle="tab">PHP</a></li>
    <li role="presentation"><a href="#css" aria-controls="css" role="tab" data-toggle="tab">CSS</a></li>
    <li role="presentation"><a href="#fields" aria-controls="fields" role="tab" data-toggle="tab">Fields Helper</a></li>
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="php">' . $content1 . '</div>
    <div role="tabpanel" class="tab-pane" id="css">' . $content2 . '</div>
    <div role="tabpanel" class="tab-pane" id="fields">' . $content3 . '</div>
  </div>
</div>';

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('xcore_code_snippets_frontend_form'), false);
$fragment->setVar('body', $content, false);

echo $fragment->parse('core/page/section.php');
