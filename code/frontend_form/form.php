<div id="rexx-form">
	<?php
	// helper vars, don't touch!
	$message = '';
	$formData = [];
	$validateMessages = [];
	$validateAlerts = [];
	$showForm = true;
	$formValid = true;
	$redirect = false;

	/* ------------------------------------------------------------------ */
	/* 1) Settings                                                        */
	/* ------------------------------------------------------------------ */

	$redirectToConfirmationArticle = false; // if false user will see form with error messages from $validateMessages (see 3))
	$confirmationArticleId = 0; // only needed if $redirectToConfirmationArticle is true

	// mail settings: see 4)

	/* ------------------------------------------------------------------ */
	/* 2) Retrieve and sanitize form data                                 */
	/* ------------------------------------------------------------------ */

	$formData['name'] = rexx::sanitizeFormValue('name', rexx::SANITIZE_TYPE_STRING);
	$formData['email'] = rexx::sanitizeFormValue('email', rexx::SANITIZE_TYPE_EMAIL);
	$formData['phone'] = rexx::sanitizeFormValue('phone', rexx::SANITIZE_TYPE_STRING);
	$formData['message'] = rexx::sanitizeFormValue('message', rexx::SANITIZE_TYPE_STRING);
	$formData['robotron'] = rexx::sanitizeFormValue('robotron', rexx::SANITIZE_TYPE_STRING); // this is a honeypot for spambots!
	$formData['submit'] = rexx::sanitizeFormValue('submit', rexx::SANITIZE_TYPE_STRING); 

	/* ------------------------------------------------------------------ */
	/* 3) Validate form data                                              */
	/* ------------------------------------------------------------------ */

	if ($formData['submit'] == 'submit') {	
		if (!rexx::validateFormValue($formData['name'], rexx::VALIDATE_TYPE_NOT_EMPTY)) {
			$formValid = false;
			$validateAlerts['name'] = true;
			$validateMessages['name'] = '';
		}

		if (!rexx::validateFormValue($formData['email'], rexx::VALIDATE_TYPE_EMAIL)) {
			$formValid = false;
			$validateAlerts['email'] = true;
			$validateMessages['email'] = '';
		}

		if (!rexx::validateFormValue($formData['message'], rexx::VALIDATE_TYPE_NOT_EMPTY)) {
			$formValid = false;
			$validateAlerts['message'] = true;
			$validateMessages['message'] = '';
		}

		// robotron: humans will let honeypot field empty because it is hidden by css but spambots will most likeley fill it out and form will not be send then.
		if (!rexx::validateFormValue($formData['robotron'], rexx::VALIDATE_TYPE_EMPTY)) {
			$formValid = false;
			$validateAlerts['robotron'] = true;
			$validateMessages['robotron'] = '';
		}

		if ($formValid) {
			/* ------------------------------------------------------------------ */
			/* 4) Mail settings                                                   */
			/* ------------------------------------------------------------------ */

			$mailTo = rexx::getDefaultGlobalValue('email_to');
			$mailFrom = 'noreply@' . rexx::getServerHost(true);
			$mailSubject = 'Neue Kontakt-Anfrage von ' . rexx::getServerHost();

			$mailBody = '';
			$mailBody .= rexx::getDefaultString('form_name') . ': ' . $formData['name'] . PHP_EOL;
			$mailBody .= rexx::getDefaultString('form_email') . ': ' . $formData['email'] . PHP_EOL;
			$mailBody .= rexx::getDefaultString('form_phone') . ': ' . $formData['phone'] . PHP_EOL;
			$mailBody .= rexx::getDefaultString('form_message') . ': ' . $formData['message'] . PHP_EOL;

			// init mailer
			$mail = new rex_mailer();
			$mail->AddAddress($mailTo, $mailTo);
			$mail->WordWrap = 80;
			$mail->FromName = $mailFrom;
			$mail->From = $mailFrom;
			$mail->Sender = $mailFrom;
			$mail->Subject = $mailSubject;
			$mail->Body = nl2br($mailBody);
			$mail->AltBody = strip_tags($mailBody);

			// send mail
			if ($mail->send()) {
				$redirect = true;
				$showForm = false;
				$message = rexx::getString('mail_success_msg');
			} else {
				$redirect = false;
				$showForm = false;
				$message = rexx::getString('mail_error_msg');

				// error message is only visible to logged in users
				if (rex_backend_login::createUser() && $mail->ErrorInfo != '') {
					$message .= '<br /><br /><strong>PHPMailer Error:</strong>' . $mail->ErrorInfo;
				}
			}
		} else {
			// not validated
			$showForm = true;
			$message = '<span class="validate-alert-text">' . rexx::getString('form_not_valid_msg') . '</span>';
		}

		if ($redirectToConfirmationArticle && $confirmationArticleId > 0 && $redirect) {
			// redirect to confirmation article
			rexx::redirectToUrl(rexx::getFullUrl($confirmationArticleId) . '#', 303);
		} else {
			// message output
			if ($message != '') {
				// main message
				$msgOut = '<p class="form-messages">' . $message . '</p>';

				// message details
				if (count($validateMessages) > 0) {
					$msgItems = '';

					foreach ($validateMessages as $msg) {
						if (trim($msg)) {
							$msgItems .= '<li>' . $msg . '</li>';
						}
					}

					if ($msgItems != '') {
						$msgOut .= '<ul class="validate-alert-list">' . $msgItems . '</ul>';
					}
				}
			}

			echo $msgOut;
		}
	}

	if ($showForm) {
		/* ------------------------------------------------------------------ */
		/* 5) Form fields                                                     */
		/* ------------------------------------------------------------------ */
	?>
		<form action="<?php echo rexx::getUrl(rexx::getCurrentArticleId(), rexx::getCurrentClangId()); ?>#rexx-form" method="post">
			<div class="control textfield <?php echo rexx::getValidateAlertClass('name', $validateAlerts); ?>">
				<label for="name"><?php echo rexx::getRequiredString('form_name', true); ?></label>
				<input type="text" id="name" name="name" value="<?php echo $formData['name']; ?>" placeholder="<?php echo rexx::getRequiredPlaceholderString('form_name', true); ?>" />
			</div>

			<div class="control textfield <?php echo rexx::getValidateAlertClass('email', $validateAlerts); ?>">
				<label for="email"><?php echo rexx::getRequiredString('form_email', true); ?></label>
				<input type="email" id="name" name="email" value="<?php echo $formData['email']; ?>" placeholder="<?php echo rexx::getRequiredPlaceholderString('form_email', true); ?>" />
			</div>

			<div class="control textfield <?php echo rexx::getValidateAlertClass('phone', $validateAlerts); ?>">
				<label for="phone"><?php echo rexx::getRequiredString('form_phone', false); ?></label>
				<input type="text" id="phone" name="phone" value="<?php echo $formData['phone']; ?>" placeholder="<?php echo rexx::getRequiredPlaceholderString('form_phone', false); ?>" />
			</div>

			<div class="control textarea <?php echo rexx::getValidateAlertClass('message', $validateAlerts); ?>">
				<label for="message"><?php echo rexx::getRequiredString('form_message', true); ?></label>
				<textarea id="message" name="message" placeholder="<?php echo rexx::getRequiredPlaceholderString('form_message', true); ?>"><?php echo $formData['message']; ?></textarea>
			</div>

			<div class="control textfield robotron <?php echo rexx::getValidateAlertClass('robotron', $validateAlerts); ?>">
				<label for="robotron"><?php echo rexx::getRequiredString('form_robotron', true); ?></label>
				<input type="text" id="robotron" name="robotron" value="<?php echo $formData['robotron']; ?>" placeholder="<?php echo rexx::getRequiredPlaceholderString('form_robotron', true); ?>" />
			</div>

			<p class="required-msg"><span class="required">*</span> <?php echo rexx::getString('form_required_fields'); ?></p>

			<div class="control submit-button">
				<button class="button" name="submit" type="submit" value="submit"><?php echo rexx::getString('form_submit'); ?></button>
			</div>
		</form>
	<?php
	}
	?>
</div>
