<div class="rexx-markdown">
	<div class="table-container">
		<?php
		$file = rex_file::get($this->getPath('docs/rexx_api.md'));
		$file = str_replace('Class: rexx', 'Class: rexx extends rex', $file);
		$file = str_replace('Function', 'Method', $file);

		$parsedown = new ParsedownExtra();
		$content = $parsedown->text($file);

		$fragment = new rex_fragment();
		$fragment->setVar('title', $this->i18n('rexx_api'));
		$fragment->setVar('body', $content, false);

		echo $fragment->parse('core/page/section.php');
		?>
	</div>
</div>
