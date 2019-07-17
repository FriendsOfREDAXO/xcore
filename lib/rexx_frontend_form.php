<?php

class rexx_frontend_form {
	// sanitize types for rexx_frontend_form::sanitizeFormValue()
	const SANITIZE_TYPE_STRING = 1;
	const SANITIZE_TYPE_EMAIL = 2;
	const SANITIZE_TYPE_INT = 3;
	const SANITIZE_TYPE_URL = 4;
	const SANITIZE_TYPE_RAW = 5;

	// validate types for rexx_frontend_form::validateFormValue()
	const VALIDATE_TYPE_NOT_EMPTY = 1;
	const VALIDATE_TYPE_EMPTY = 2;
	const VALIDATE_TYPE_EMAIL = 3;
	const VALIDATE_TYPE_INT = 4;
	const VALIDATE_TYPE_URL = 5;

	/**
	 * Sanitizes a form value by given sanitize type constant like rexx_frontend_form::SANITIZE_TYPE_STRING, rexx_frontend_form::SANITIZE_TYPE_EMAIL, rexx_frontend_form::SANITIZE_TYPE_INT, rexx_frontend_form::SANITIZE_TYPE_URL.
	 *
	 * @param string $valueName
	 * @param int $sanitizeType
	 *
	 * @return string
	 *
	 */
	public static function sanitizeFormValue($valueName, $sanitizeType) {
		$filter = 0;

		switch ($sanitizeType) {
			case rexx::SANITIZE_TYPE_STRING:
				$filter = FILTER_SANITIZE_STRING;
				break;	
			case rexx::SANITIZE_TYPE_EMAIL:
				$filter = FILTER_SANITIZE_EMAIL;
				break;
			case rexx::SANITIZE_TYPE_INT:
				$filter = FILTER_SANITIZE_NUMBER_INT;
				break;
			case rexx::SANITIZE_TYPE_URL:
				$filter = FILTER_SANITIZE_URL;
				break;
			case rexx::SANITIZE_TYPE_RAW:
				$filter = FILTER_UNSAFE_RAW;
				break;	
			default:
				throw new InvalidArgumentException('Value of $sanitizeType in sanitizeFormValue() call not recongized!');
		}

		if (isset($_POST[$valueName])) {
			$value = $_POST[$valueName];
			$value = filter_var($value, $filter);

			if ($value !== false) {
				$value = trim($value);

				if ($sanitizeType != self::SANITIZE_TYPE_RAW) {
					$value = stripslashes($value);
					$value = htmlspecialchars($value);
				}

				return $value;
			}
		} 

		return '';
	}

	/**
	 * Validates a form value by given validate type constant like rexx_frontend_form::VALIDATE_TYPE_NOT_EMPTY, rexx_frontend_form::VALIDATE_TYPE_EMPTY, rexx_frontend_form::VALIDATE_TYPE_EMAIL, rexx_frontend_form::VALIDATE_TYPE_INT, rexx_frontend_form::VALIDATE_TYPE_URL.
	 *
	 * @param string $value
	 * @param int $validateType
	 *
	 * @return bool
	 *
	 */
	public static function validateFormValue($value, $validateType) {
		$isValid = false;

		switch ($validateType) {
			case rexx::VALIDATE_TYPE_EMAIL:
				if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
					$isValid = true;
				}
				break;	
			case rexx::VALIDATE_TYPE_INT:
				if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_URL:
				if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_NOT_EMPTY:
				if (!empty($value) || $value === '0') {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_EMPTY:
				if (empty($value)) {
					$isValid = true;
				}
				break;
			default:
				throw new InvalidArgumentException('Value of $validateType in validateFormValue() call not recongized!');			
		}

		return $isValid;
	}

	/**
	 * Helper for getting validate class if $valueName in $valueArray.
	 *
	 * @param string $valueName
	 * @param string[] $valueArray
	 *
	 * @return string
	 *
	 */
	public static function getValidateAlertClass($valueName, $valueArray, $validateClass = 'validate-alert') {
		if (isset($valueArray[$valueName])) { 
			return $validateClass;
		} else {
			return '';
		}
	}

	/**
	 * Helper for getting required string with extra required span tag.
	 *
	 * @param string $key
	 * @param bool $required
	 * @param string $requiredClass
	 * @param string $requiredContent
	 *
	 * @return string
	 *
	 */
	public static function getRequiredString($key, $required = false, $requiredClass = 'required', $requiredContent = '*') {
		if ($required) {
			return rexx::getString($key) . ' <span class="' . $requiredClass . '">' . $requiredContent . '</span>';
		} else {
			return rexx::getString($key);
		}
	}

	/**
	 * Helper for getting required placeholder string with extra required content string.
	 *
	 * @param string $key
	 * @param bool $required
	 * @param string $requiredContent
	 *
	 * @return string
	 *
	 */
	public static function getRequiredPlaceholderString($key, $required = false, $requiredContent = '*') {
		if ($required) {
			return rexx::getString($key) . $requiredContent;
		} else {
			return rexx::getString($key);
		}
	}
}

