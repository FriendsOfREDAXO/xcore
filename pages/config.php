<?php

$content = '';
$buttons = '';

// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1') {
    $this->setConfig(rex_post('config', [
        ['title_delimeter', 'string'],
        ['url_ending', 'string'],
        ['css_dir', 'string'],
        ['js_dir', 'string'],
        ['image_dir', 'string'],
        ['favicon_dir', 'string'],
        ['smart_redirects', 'int'],
        ['offline_404_mode', 'int'],
        ['show_meta_frontend_link', 'int'],
        ['xcore_styles', 'int'],
        ['allow_downloads', 'int'],
    ]));

	rex_config::save();

    echo rex_view::success($this->i18n('config_saved'));

	rex_yrewrite::deleteCache();
}

// title_delimeter
$formElements = [];
$n = [];
$n['label'] = '<label for="title_delimeter">' . $this->i18n('config_title_delimeter') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="title_delimeter" name="config[title_delimeter]" value="' . $this->getConfig('title_delimeter') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// url_ending
$formElements = [];
$n = [];
$n['label'] = '<label for="url_ending">' . $this->i18n('config_url_ending') . '</label>';
$select = new rex_select();
$select->setId('url_ending');
$select->setAttribute('class', 'form-control');
$select->setName('config[url_ending]');
$select->addOption('.html', '.html');
$select->addOption('/', '/');
$select->addOption('(ohne)', '');
$select->setSelected($this->getConfig('url_ending'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// css_dir
$formElements = [];
$n = [];
$n['label'] = '<label for="css_dir">' . $this->i18n('config_css_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="css_dir" name="config[css_dir]" value="' . $this->getConfig('css_dir') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// js_dir
$formElements = [];
$n = [];
$n['label'] = '<label for="js_dir">' . $this->i18n('config_js_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="js_dir" name="config[js_dir]" value="' . $this->getConfig('js_dir') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// image_dir
$formElements = [];
$n = [];
$n['label'] = '<label for="image_dir">' . $this->i18n('config_image_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="image_dir" name="config[image_dir]" value="' . $this->getConfig('image_dir') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// favicon_dir
$formElements = [];
$n = [];
$n['label'] = '<label for="favicon_dir">' . $this->i18n('config_favicon_dir') . '</label>';
$n['field'] = '<input class="form-control" type="text" id="favicon_dir" name="config[favicon_dir]" value="' . $this->getConfig('favicon_dir') . '"/>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// smart_redirects
$formElements = [];
$n = [];
$n['label'] = '<label for="smart_redirects">' . $this->i18n('config_smart_redirects') . '</label>';
$n['field'] = '<input type="checkbox" id="smart_redirects" name="config[smart_redirects]"' . (!empty($this->getConfig('smart_redirects')) && $this->getConfig('smart_redirects') == '1' ? ' checked="checked"' : '') . ' value="1" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

// offline_404_mode
$formElements = [];
$n = [];
$n['label'] = '<label for="offline_404_mode">' . $this->i18n('config_offline_404_mode') . '</label>';
$n['field'] = '<input type="checkbox" id="offline_404_mode" name="config[offline_404_mode]"' . (!empty($this->getConfig('offline_404_mode')) && $this->getConfig('offline_404_mode') == '1' ? ' checked="checked"' : '') . ' value="1" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

// show_meta_frontend_link
$formElements = [];
$n = [];
$n['label'] = '<label for="show_meta_frontend_link">' . $this->i18n('config_show_meta_frontend_link') . '</label>';
$n['field'] = '<input type="checkbox" id="show_meta_frontend_link" name="config[show_meta_frontend_link]"' . (!empty($this->getConfig('show_meta_frontend_link')) && $this->getConfig('show_meta_frontend_link') == '1' ? ' checked="checked"' : '') . ' value="1" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

// xcore_styles
$formElements = [];
$n = [];
$n['label'] = '<label for="xcore_styles">' . $this->i18n('config_xcore_styles') . '</label>';
$n['field'] = '<input type="checkbox" id="xcore_styles" name="config[xcore_styles]"' . (!empty($this->getConfig('xcore_styles')) && $this->getConfig('xcore_styles') == '1' ? ' checked="checked"' : '') . ' value="1" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

// allow downloads
$formElements = [];
$n = [];
$n['label'] = '<label for="allow_downloads">' . $this->i18n('config_allow_downloads') . '</label>';
$n['field'] = '<input type="checkbox" id="allow_downloads" name="config[allow_downloads]"' . (!empty($this->getConfig('allow_downloads')) && $this->getConfig('allow_downloads') == '1' ? ' checked="checked"' : '') . ' value="1" />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

// Save-Button
$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('config_save') . '">' . $this->i18n('config_save') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
    ' . $buttons . '
</fieldset>
';

// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('config'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');

$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
    ' . $output . '
</form>
';

echo $output;
