<?php
// - only execute this code once!
// - modify for your needs then add to boot.php of project addon and reload backend one time, then remove it!
// - global_settings addon needed!

if (rex::isBackend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		$clang = 1;
		$prio = 100;
		$rc = [];

		$fields = [];
		$fields[] = ['label' => 'E-Mail Empfänger', 'name' => 'email_to', 'value' => '', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_name', 'value' => 'Name', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_email', 'value' => 'E-Mail', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_phone', 'value' => 'Telefon', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_message', 'value' => 'Ihre Nachricht', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_robotron', 'value' => 'Bitte lassen Sie dieses Feld leer!', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_submit', 'value' => 'Senden', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_required_fields', 'value' => 'Pflichtfelder', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'form_not_valid_msg', 'value' => 'Bitte füllen Sie alle Pflichtfelder aus!', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'mail_success_msg', 'value' => 'Vielen Dank für Ihre Anfrage. Wir melden uns so bald wie möglich bei Ihnen.', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];
		$fields[] = ['label' => '', 'name' => 'mail_error_msg', 'value' => 'Leider ist ein Fehler aufgetreten. Bitte versuchen Sie es später wieder.', 'type' => REX_GLOBAL_SETTINGS_FIELD_TEXT];

		foreach ($fields as $field) {
			// create fields
			$rc[$field['name']] = rex_global_settings_add_field($field['label'], 'glob_' . $field['name'], $prio++, '', $field['type'], '');

			// add values
			$sql = rex_sql::factory();
			$sql->setQuery('UPDATE ' . rex::getTablePrefix() . 'global_settings SET `' . 'glob_' . $field['name'] . '` = "' . $field['value'] . '" WHERE `clang` = ' .$clang);
		}

		echo '<p>Return:</p>';
		out($rc);
	});
} else {
	echo 'Only for backend usage!';
}
?>
