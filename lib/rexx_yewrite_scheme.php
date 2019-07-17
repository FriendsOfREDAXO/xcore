<?php
class rexx_yrewrite_scheme extends rex_yrewrite_scheme {
	public function appendArticle($path, rex_article $art, rex_yrewrite_domain $domain) {
		$urlEnding = rexx::getUrlEnding();

		if ($art->isStartArticle() && $domain->getMountId() != $art->getId()) {
			return $path . $urlEnding;
		}

		return $path . '/' . $this->normalize($art->getName()) . $urlEnding;
	}

    public function normalize($string, $clang = 1) {
		$langCode = rexx::getLangCode();
		$globalSpecialChars = rexx_clang::getGlobalPresetValue('special_chars');
		$globalSpecialCharsRewrite = rexx_clang::getGlobalPresetValue('special_chars_rewrite');
		$specialChars = rexx_clang::getPresetValue($langCode, 'special_chars');
		$specialCharsRewrite = rexx_clang::getPresetValue($langCode, 'special_chars_rewrite');

		if ($specialChars == null) {
			// code not found, use defaults
			$specialChars = ['Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß', '/', '®', '©', '™'];
			$specialCharsRewrite = ['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss', '-', '', '', ''];

		}

		// rerplace lang specific global chars
		$string = str_replace($globalSpecialChars, $globalSpecialCharsRewrite, $string);

		// rerplace lang specific chars
		$string = str_replace($specialChars, $specialCharsRewrite, $string);

		// replace non letter or digits by -
		$string = preg_replace('~[^\pL\d]+~u', '-', $string);

		// transliterate
		$string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

		// remove unwanted characters
		$string = preg_replace('~[^-\w]+~', '', $string);

		// trim
		$string = trim($string, '-');

		// remove duplicate -
		$string = preg_replace('~-+~', '-', $string);

		// lowercase
		$string = strtolower($string);

		// urlencode
		$string = urlencode($string);

		return $string;
	}
}
