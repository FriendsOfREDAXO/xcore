<?php

class rexx_clang {
	protected static $langPresets = [
		'cs' => [
			'original_name' => 'české',
			'code' => 'cs',
			'region_code' => 'cs-CZ',
			'url_slug' => 'cs',
			'hreflang' => 'cs',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'Č', 'č', 'ć', 'È', 'è', 'É', 'é', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ł', 'ł', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', 'ź', 'ż', 'ž', '&'],
			'special_chars_rewrite' => ['A', 'a', 'C', 'c', 'c', 'E', 'e', 'E', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'L', 'l', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'z', 'z', 'z', 'a']
		],
		'da' => [
			'original_name' => 'dansk',
			'code' => 'da',
			'region_code' => 'da-DK',
			'url_slug' => 'da',
			'hreflang' => 'da',
			'dir' => 'ltr',
			'special_chars' => ['Å', 'å', 'Æ', 'æ', 'Ø', 'ø', 'É', 'é', 'Á', 'á', '&'],
			'special_chars_rewrite' => ['Aa', 'aa', 'Ae', 'ae', 'Oe', 'oe', 'E', 'e', 'A', 'a', 'og']
		],
		'de' => [
			'original_name' => 'deutsch',
			'code' => 'de',
			'region_code' => 'de-DE',
			'url_slug' => 'de',
			'hreflang' => 'de',
			'dir' => 'ltr',
			'special_chars' => ['Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß', '&'],
			'special_chars_rewrite' => ['Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss', 'und']
		],
		'en' => [
			'original_name' => 'english',
			'code' => 'en',
			'region_code' => 'en-GB',
			'url_slug' => 'en',
			'hreflang' => 'en',
			'dir' => 'ltr',
			'special_chars' => ['&'],
			'special_chars_rewrite' => ['and']
		],
		'es' => [
			'original_name' => 'español',
			'code' => 'es',
			'region_code' => 'es-ES',
			'url_slug' => 'es',
			'hreflang' => 'es',
			'dir' => 'ltr',
			'special_chars' => ['Á', 'á', 'ç', 'É', 'é', 'Í', 'í', 'Ñ', 'ñ', 'Ó', 'ó', 'Ú', 'ú', 'ü', '&'],
			'special_chars_rewrite' => ['A', 'a', 'c', 'E', 'e', 'I', 'i', 'N', 'n', 'O', 'o', 'U', 'u', 'u', 'y']
		],
		'fr' => [
			'original_name' => 'française',
			'code' => 'fr',
			'region_code' => 'fr-FR',
			'url_slug' => 'fr',
			'hreflang' => 'fr',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'Á', 'á', 'ç', 'È', 'è', 'É', 'é', 'ë', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', '&'],
			'special_chars_rewrite' => ['A', 'a', 'A', 'a', 'c', 'E', 'e', 'E', 'e', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'et']
		],
		'it' => [
			'original_name' => 'italiano',
			'code' => 'it',
			'region_code' => 'it-IT',
			'url_slug' => 'it',
			'hreflang' => 'it',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'È', 'è', 'É', 'é', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', '&'],
			'special_chars_rewrite' => ['A', 'a', 'E', 'e', 'E', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'e']
		],
		'hu' => [
			'original_name' => 'magyar',
			'code' => 'hu',
			'region_code' => 'hu-HU',
			'url_slug' => 'hu',
			'hreflang' => 'hu',
			'dir' => 'ltr',
			'special_chars' => ['Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ö', 'ö', 'Ő', 'ő', 'Ú', 'ú', 'Ü', 'ü', 'Ű', 'ű', '&'],
			'special_chars_rewrite' => ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'es']
		],
		'nl' => [
			'original_name' => 'nederlands',
			'code' => 'nl',
			'region_code' => 'nl-NL',
			'url_slug' => 'nl',
			'hreflang' => 'nl',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'È', 'è', 'É', 'é', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', '&'],
			'special_chars_rewrite' => ['A', 'a', 'E', 'e', 'E', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'en']
		],
		'no' => [
			'original_name' => 'norsk',
			'code' => 'no',
			'region_code' => 'no-NO',
			'url_slug' => 'no',
			'hreflang' => 'no',
			'dir' => 'ltr',
			'special_chars' => ['Æ', 'æ', 'Ø', 'ø', 'Å', 'å', '&'],
			'special_chars_rewrite' => ['Ae', 'ae', 'Oe', 'oe', 'A', 'a', 'og']
		],
		'pl' => [
			'original_name' => 'polska',
			'code' => 'pl',
			'region_code' => 'pl-PL',
			'url_slug' => 'pl',
			'hreflang' => 'pl',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'Č', 'č', 'ć', 'È', 'è', 'É', 'é', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ł', 'ł', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', 'ź', 'ż', 'ž', '&'],
			'special_chars_rewrite' => ['A', 'a', 'C', 'c', 'c', 'E', 'e', 'E', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'L', 'l', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'z', 'z', 'z', 'i']
		],
		'pt' => [
			'original_name' => 'português',
			'code' => 'pt',
			'region_code' => 'pt-PT',
			'url_slug' => 'pt',
			'hreflang' => 'pt',
			'dir' => 'ltr',
			'special_chars' => ['À', 'à', 'á', 'ã', 'ç', 'È', 'è', 'É', 'é', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ò', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', '&'],
			'special_chars_rewrite' => ['A', 'a', 'a', 'a', 'c', 'E', 'e', 'E', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'O', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'e']
		],
		'sv' => [
			'original_name' => 'svensk',
			'code' => 'sv',
			'region_code' => 'sv-SE',
			'url_slug' => 'sv',
			'hreflang' => 'sv',
			'dir' => 'ltr',
			'special_chars' => ['Å', 'å', 'Ä', 'ä', 'Ö', 'ö', '&'],
			'special_chars_rewrite' => ['A', 'a', 'Oe', 'oe', 'Ae', 'ae', 'och']
		],
		'tr' => [
			'original_name' => 'türk',
			'code' => 'tr',
			'region_code' => 'tr-TR',
			'url_slug' => 'tr',
			'hreflang' => 'tr',
			'dir' => 'ltr',
			'special_chars' => ['Ç', 'ç', 'Ş', 'ş', 'Ğ', 'ğ', 'İ', 'ı', 'Ö', 'ö', 'Ü', 'ü', '&'],
			'special_chars_rewrite' => ['C', 'c', 'S', 's', 'G', 'g', 'I', 'i', 'O', 'o', 'U', 'u', 've']
		],
	];

	protected static $globalLangPresets = [
		'special_chars' => [],
		'special_chars_rewrite' => []
	];

	public static function getPresetValue($code, $key) {
		if (isset(self::$langPresets[$code][$key])) {
			return self::$langPresets[$code][$key];
		} else {
			return null;
		}
	}

	public static function setPresetValue($code, $key, $value) {
		if (isset(self::$langPresets[$code][$key])) {
			self::$langPresets[$code][$key] = $value;
		}
	}

	public static function getGlobalPresetValue($key) {
		if (isset(self::$globalLangPresets[$key])) {
			return self::$globalLangPresets[$key];
		} else {
			return null;
		}
	}

	public static function setGlobalPresetValue($key, $value) {
		if (isset(self::$globalLangPresets[$key])) {
			self::$globalLangPresets[$key] = $value;
		}
	}

	public static function setGlobalLangPreset($specialChars = [], $specialCharsRewrite = []) {
		rexx_clang::setGlobalPresetValue('special_chars', $specialChars);
		rexx_clang::setGlobalPresetValue('special_chars_rewrite', $specialCharsRewrite);
	}

	public static function setLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite) {
		rexx_clang::setPresetValue($code, 'original_name', $originalName);
		rexx_clang::setPresetValue($code, 'code', $code);
		rexx_clang::setPresetValue($code, 'region_code', $regionCode);
		rexx_clang::setPresetValue($code, 'url_slug', $urlSlug);
		rexx_clang::setPresetValue($code, 'hreflang', $hreflang);
		rexx_clang::setPresetValue($code, 'dir', $dir);
		rexx_clang::setPresetValue($code, 'special_chars', $specialChars);
		rexx_clang::setPresetValue($code, 'special_chars_rewrite', $specialCharsRewrite);
	}

	public static function addLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite) {
		if (!isset(self::$langPresets[$code])) {
			self::$langPresets[$code] = [
				'original_name' => $originalName,
				'code' => $code,
				'region_code' => $regionCode,
				'url_slug' => $urlSlug,
				'hreflang' => $hreflang,
				'dir' => $dir,
				'special_chars' => $specialChars,
				'special_chars_rewrite' => $specialCharsRewrite
			];
		}
	}
}
