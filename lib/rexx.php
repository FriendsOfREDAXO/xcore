<?php

/**
 * The main class of X-Core AddOn inherited from class rex.
 */
class rexx extends rex {
	protected static $titleDelimeter;
	protected static $urlEnding;
	protected static $cssDir;
	protected static $jsDir;
	protected static $imageDir;
	protected static $favIconsDir;

	protected static $urlStart;
	protected static $serverSubDir;
	protected static $isSubDirInstall;

	const MEDIATYPES_DIR = 'mediatypes';
	const DOWNLOAD_DIR = 'download';
	const DEFAULT_ROBOTS_ARCHIVE_FLAG = 'noarchive';

	// sort types for rexx::sortArticles()
	const ARTICLE_SORT_TYPE_PRIO = 1;
	const ARTICLE_SORT_TYPE_NAME = 2;
	const ARTICLE_SORT_TYPE_CREATEDATE = 3;
	const ARTICLE_SORT_TYPE_UPDATEDATE = 4;

	// sanitize types for rexx::sanitizeFormValue()
	const SANITIZE_TYPE_STRING = rexx_frontend_form::SANITIZE_TYPE_STRING;
	const SANITIZE_TYPE_EMAIL = rexx_frontend_form::SANITIZE_TYPE_EMAIL;
	const SANITIZE_TYPE_INT = rexx_frontend_form::SANITIZE_TYPE_INT;
	const SANITIZE_TYPE_URL = rexx_frontend_form::SANITIZE_TYPE_URL;
	const SANITIZE_TYPE_RAW = rexx_frontend_form::SANITIZE_TYPE_RAW;

	// validate types for rexx::validateFormValue()
	const VALIDATE_TYPE_NOT_EMPTY = rexx_frontend_form::VALIDATE_TYPE_NOT_EMPTY;
	const VALIDATE_TYPE_EMPTY = rexx_frontend_form::VALIDATE_TYPE_EMPTY;
	const VALIDATE_TYPE_EMAIL = rexx_frontend_form::VALIDATE_TYPE_EMAIL;
	const VALIDATE_TYPE_INT = rexx_frontend_form::VALIDATE_TYPE_INT;
	const VALIDATE_TYPE_URL = rexx_frontend_form::VALIDATE_TYPE_URL;

	/**
	 * Initilizes the rexx class.
	 * X-Core calls this automatically.
	 */
	public static function init() {	
		self::$titleDelimeter = rex_config::get('xcore', 'title_delimeter');
		self::$urlEnding = rex_config::get('xcore', 'url_ending');
		self::$cssDir = rex_config::get('xcore', 'css_dir');
		self::$jsDir = rex_config::get('xcore', 'js_dir');
		self::$imageDir = rex_config::get('xcore', 'image_dir');
		self::$favIconsDir = rex_config::get('xcore', 'favicon_dir');

		// pull apart server url
		$urlParts = parse_url(rexx::getServerUrl());

		if (isset($urlParts['path']) && isset($urlParts['scheme'])) { // if scheme is empty don't count on path as possible subdir 
			self::$serverSubDir = trim($urlParts['path'], '/'); 
		} else {
			self::$serverSubDir = '';
		}

		// check for subdir install
		if (self::$serverSubDir == '') {
			self::$isSubDirInstall = false;
		} else {
			self::$isSubDirInstall = true;
		}
		
		// set url start
		if (self::$isSubDirInstall) {
			// url start for subdirs
			self::$urlStart = '/'  . self::$serverSubDir . '/';
		} else {
			// url start for normal redaxo installations
			self::$urlStart = '/';
		}

		rexx_resource_includer::init(self::$cssDir, self::$jsDir, self::$imageDir, self::$favIconsDir);
	}

	/**
	 * Opposite of rexx::isBackend().
	 *
	 * @return bool
	 */
	public static function isFrontend() {
		return !rexx::isBackend();
	}	

	/**
	 * Returns the css dir specified in the config of addon.
	 *
	 * @return string
	 */
	public static function getCSSDir() {
		return self::$cssDir;
	}

	/**
	 * Returns the js dir specified in the config of addon.
	 *
	 * @return string
	 */
	public static function getJSDir() {
		return self::$jsDir;
	}

	/**
	 * Returns the image dir specified in the config of addon.
	 *
	 * @return string
	 */
	public static function getImageDir() {
		return self::$imageDir;
	}

	/**
	 * Returns the favicon dir specified in the config of addon.
	 *
	 * @return string
	 */
	public static function getFavIconDir() {
		return self::$favIconsDir;
	}

	/**
	 * Returns the the current lang code.
	 * 
	 * @param int $clangId
	 *
	 * @return string
	 */
	public static function getLangCode($clangId = null) {
		if ($clangId == null) {
			$clang = rexx::getCurrentClang();
		} else {
			$clang = rex_clang::get($clangId);
		}

		return $clang->getCode();
	}

	/**
	 * Returns number of langauges of the site.
	 *
	 * @return int
	 */
	public static function getLangCount() {
		return count(rex_clang::getAll());
	}

	/**
	 * Returns the base url of site. Same as rexx::getServer().
	 *
	 * @return string
	 */
	public static function getBaseUrl() {
		return rexx::getServer();
	}

	/**
	 * Returns title for title tag. If $siteName specified the default sitename will be ignored.
	 *
	 * @param string $siteName
	 *
	 * @return string
	 */
	public static function getTitle($siteName = '') {
		if ($siteName == '') {
			$siteName = rexx::getSiteName();
		}
		
		if (rexx::isSiteStartArticle()) {
			$fullTitle = $siteName . ' ' . rexx::getTitleDelimiter() . ' ' . rexx::getTitlePart();
		} else {
			$fullTitle = rexx::getTitlePart() . ' ' . rexx::getTitleDelimiter() . ' ' . $siteName;
		}

		return htmlspecialchars($fullTitle);
	}

	/**
	 * Checks if the current article is the site start article.
	 *
	 * @return bool
	 */
	public static function isSiteStartArticle() {
		if (rexx::getSiteStartArticleId() == rexx::getCurrentArticleId()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns the sitename. Same as rexx::getServerName().
	 *
	 * @return string
	 */
	public static function getSiteName() {
		return rexx::getServerName();
	}

	/**
	 * Returns the title delimiter for the title tag.
	 *
	 * @return string
	 */
	public static function getTitleDelimiter() {
		return self::$titleDelimeter;
	}

	/**
	 * Returns the current title part without sitename.
	 *
	 * @return string
	 */
	public static function getTitlePart() {
		$curArticle = rexx::getCurrentArticle();

		if ($curArticle->getValue('yrewrite_title') != '') {
			return $curArticle->getValue('yrewrite_title'); 
		} else {
			return $curArticle->getValue('name');
        }
	}

	/**
	 * Returns the current description for the description tag.
	 *
	 * @return string
	 */
	public static function getDescription() {
		$curArticle = rexx::getCurrentArticle();
		$description = $curArticle->getValue('yrewrite_description');
		$description = str_replace(["\n","\r"], [' ',''], $description);

		return $description;
	}

	/**
	 * Returns the current keywords for the keywords tag. 
	 * At the moment "art_keywords" meta info field of article will be used (if present).
	 *
	 * @return string
	 */
	public static function getKeywords() {
		$article = rexx::getCurrentArticle();

		return $article->getValue('art_keywords');
	}

	/**
	 * Returns the current robot rules for the robots tag.
	 *
	 * @return string
	 */
	public static function getRobotRules() {
		$curArticle = rexx::getCurrentArticle();

	     if ($curArticle->getValue('yrewrite_index') == 1 || ($curArticle->getValue('yrewrite_index') == 0 && $curArticle->isOnline())) {
            return 'index, follow, ' . self::DEFAULT_ROBOTS_ARCHIVE_FLAG;
        } else {
            return 'noindex, follow, ' . self::DEFAULT_ROBOTS_ARCHIVE_FLAG;
        }
	}

	/**
	 * Returns the current canonical url.
	 *
	 * @return string
	 */
	public static function getCanonicalUrl() {
		$seo = new rex_yrewrite_seo();

		return $seo->getCanonicalUrl();
	}

	/**
	 * Returns the url start slug.
	 *
	 * @return string
	 */
	public static function getUrlStart() {
		return self::$urlStart;
	}

	/**
	 * Returns the url ending.
	 *
	 * @return string
	 */
	public static function getUrlEnding() {
		return self::$urlEnding;
	}

	/**
	 * Returns the current hreflang Tags.
	 *
	 * @return string
	 */
	public static function getLangTags() {
		$seo = new rex_yrewrite_seo();
		$tags = $seo->getHreflangTags();
		$tags = str_replace('/>', '/>' . PHP_EOL . "\t", $tags);
		$tags = trim($tags, "\t");

		return $tags;
	}

	/**
	 * Returns a relative url based on the params. Same as rex_getUrl().
	 *
	 * @param int $id
	 * @param int $clang
	 * @param string[] $params
	 * @param string $separator
	 *
	 * @return string
	 */
	public static function getUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		return rex_getUrl($id, $clang, $params, $separator);
	}

	/**
	 * Returns a full url starting with http:// based on the params.
	 *
	 * @param int $id
	 * @param int $clang
	 * @param string[] $params
	 * @param string $separator
	 *
	 * @return string
	 */
	public static function getFullUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		return rexx::getServerUrl() . rexx::getTrimmedUrl($id, $clang, $params, $separator);
	}

	/**
	 * Returns a relative url based on the params without url start slug, http etc.
	 *
	 * @param int $id
	 * @param int $clang
	 * @param string[] $params
	 * @param string $separator
	 *
	 * @return string
	 */
	public static function getTrimmedUrl($id = null, $clang = null, array $params = [], $separator = '&amp;') {
		$url = rexx::getUrl($id, $clang, $params, $separator);

		if (strpos($url, rexx::getServerUrl()) === 0) {
			$url = substr($url, strlen(rexx::getServerUrl()));
		}

		return ltrim($url, "./");
	}

	/**
	 * Returns value from "tracking_code" field from global_settings addon. 
	 *
	 * @return string
	 */
	public static function getTrackingCode() {
		return rex_global_settings::getDefaultValue('tracking_code', true);
	}

	/**
	 * Returns the description of the given mediatype.
	 *
	 * @param string $mediaType
	 *
	 * @return string
	 */
	public static function getMediaTypeDescription($mediaType) {
		$query = 'SELECT * FROM '. rexx::getTablePrefix() .'media_manager_type WHERE name LIKE "' . $mediaType . '"';

		$sql = rex_sql::factory();
		$sql->setQuery($query);

		if ($sql->getRows() > 0) {
			return $sql->getValue('description');
		} else {
			return $mediaType;
		}
	}

	/**
	 * Gives an attribute value pair for html usage. If $value is empty, an empty string will be returned.
	 *
	 * @param string $attribute
	 * @param string $value
	 *
	 * @return string
	 */
	public static function getHtmlAttribute($attribute, $value) {
		if ($value != '' || $attribute == 'alt') {
			return ' ' . $attribute . '="' . htmlspecialchars($value) . '"';
		} else {
			return '';
		}
	}
	
	/**
	 * Includes a template. Same as REX_TEMPLATE[].
	 *
	 * @param int $templateId
	 */
	public static function includeTemplate($templateId) {
		$template = new rex_template($templateId);
		
		if ($template instanceof rex_template) {
			include_once($template->getFile());
		}
	}

	/**
	 * Returns the article content for the given article id.
	 *
	 * @param int $articleId
	 * @param int $ctypeId
	 *
	 * @return string
	 */	
	public static function getArticleContent($articleId, $ctypeId = -1) {
		$article = new rex_article_content($articleId);
		
		return $article->getArticle($ctypeId); 
	}
	
	/**
	 * Returns the content of the current article.
	 *
	 * @param int $ctypeId
	 *
	 * @return string
	 */	
	public static function getCurrentArticleContent($ctypeId = -1) {
		return rexx::getArticleContent(rexx::getCurrentArticleId(), $ctypeId); 
	}

	/**
	 * Returns an link tag with url and name from specified article id.
	 *
	 * @param int $articleId
	 *
	 * @return string
	 */	
	public static function getArticleLink($articleId) {
		return '<a href="' . rexx::getUrl($articleId) . '">' . rexx::getArticleName($articleId) . '</a>';
	}

	/**
	 * Returns backend url. Same as rex_url::backendController();
	 *
	 * @param array $params
	 * @param bool $escape
	 *
	 * @return string
	 */	
	public static function getBackendUrl(array $params = [], $escape = true) {
		return rex_url::backendController($params, $escape);
	}

	/**
	 * Returns the article name from specified article id.
	 *
	 * @param int $articleId
	 *
	 * @return string
	 */	
	public static function getArticleName($articleId) {
		$article = rexx::getArticle($articleId);

		if (rexx::isArticleValid($article)) {
			return $article->getName();
		} else {
			return 'Article with ID = ' . $articleId . ' not found.';
		}
	}

	/**
	 * Returns the name of current article.
	 *
	 * @return string
	 */	
	public static function getCurrentArticleName() {
		return rexx::getArticleName(rexx::getCurrentArticleId());
	}

	/**
	 * Returns the id of current article.
	 *
	 * @return int
	 */	
	public static function getCurrentArticleId() {
		return rex_article::getCurrentId();
	}

	/**
	 * Returns the current article.
	 *
	 * @return rex_article
	 */	
	public static function getCurrentArticle() {
		return rex_article::getCurrent();
	}

	/**
	 * Returns the current category id.
	 *
	 * @return int
	 */	
	public static function getCurrentCategoryId() {
		return rex_category::getCurrent()->getId();
	}

	/**
	 * Returns the current category.
	 *
	 * @return rex_category
	 */	
	public static function getCurrentCategory() {
		return rex_category::getCurrent();
	}

	/**
	 * Returns the parent id of current category.
	 *
	 * @return int
	 */	
	public static function getCurrentParentCategoryId() {
		$parentCategory = rexx::getCurrentParentCategory();

		return $category->getId();
	}

	/**
	 * Returns the parent category of current category.
	 *
	 * @return rex_category
	 */	
	public static function getCurrentParentCategory() {
		$category = rexx::getCurrentCategory();

		return $category->getParent();
	}

	/**
	 * Returns the article object from given article id.
	 *
	 * @param int $articleId
	 * @param int $clangId
	 *
	 * @return rex_article
	 */
	public static function getArticle($articleId, $clangId = null) {
		return rex_article::get($articleId, $clangId);
	}

	/**
	 * Returns the server url specified in system settings. Same as rexx::getServer().
	 *
	 * @return string
	 */
	public static function getServerUrl() {
		return rexx::getServer();
	}

	/**
	 * Returns the site startarticle id specified in system settings. Same as rex_article::getSiteStartArticleId().
	 *
	 * @return string
	 */
	public static function getSiteStartArticleId() {
		return rex_article::getSiteStartArticleId();
	}

	/**
	 * Returns the notfound article id specified in system settings. Same as rex_article::getNotfoundArticleId().
	 *
	 * @return string
	 */
	public static function getNotfoundArticleId() {
		return rex_article::getNotfoundArticleId();
	}

	/**
	 * Returns the current clang. Same as rex_clang::getCurrent().
	 *
	 * @return rex_clang
	 */
	public static function getCurrentClang() {
		return rex_clang::getCurrent();
	}

	/**
	 * Returns the current clang id. Same as rex_clang::getCurrentId().
	 *
	 * @return int
	 */
	public static function getCurrentClangId() {
		return rex_clang::getCurrentId();
	}

	/**
	 * Returns a normalized string that can be used in urls and anchor names.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function getUrlString($string) {
		$scheme = new rexx_yrewrite_scheme();

		return $scheme->normalize($string, rexx::getCurrentClangId());
	}

	/**
	 * Returns media object. Same as rex_media::get().
	 *
	 * @param string $filename
	 *
	 * @return rex_media
	 */
	public static function getMedia($filename) {
		return rex_media::get($filename);
	}

	/**
	 * Checks if media is valid.
	 *
	 * @param rex_media $media
	 *
	 * @return bool
	 */
	public static function isMediaValid($media) {
		if ($media instanceof rex_media) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns media directory.
	 *
	 * @return rex_media
	 */
	public static function getMediaDir() {
		return ltrim(rex_url::media(), '.');
	}

	/**
	 * Returns given media file with media directory.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public static function getMediaFile($file) {
		if ($file == '') {
			return '';
		} else {
			return rexx::getMediaDir() . $file;
		}
	}

	/**
	 * Returns the absolute file with path of the given media file of media dir.
	 *
	 * @param string $file 
	 *
	 * @return string
	 *
	 */
	public static function getAbsoluteMediaFile($file) {
		return rexx::getAbsolutePath(rexx::getMediaDir()) . $file;
	}

	/**
	 * Returns the absolute path of the given path starting from frontend path.
	 *
	 * @param string $path
	 *
	 * @return string
	 *
	 */
	public static function getAbsolutePath($path) {
		return rex_path::frontend() . trim($path, "/") . '/';
	}

	/**
	 * Returns the absolute file with path of the given file starting from frontend path.
	 *
	 * @param string $file 
	 *
	 * @return string
	 *
	 */
	public static function getAbsoluteFile($file) {
		return rex_path::frontend() . trim($file, "/");
	}

	/**
	 * Returns a full media url starting with http://.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public static function getFullMediaUrl($file) {
		return rexx::getServerUrl() . rexx::getMediaDirName() . '/' . $file;
	}

	/**
	 * Returns the pure media directory name.
	 *
	 * @return string
	 */
	public static function getMediaDirName() {
		return trim(rexx::getMediaDir(), "/");
	}

	/**
	 * Returns a seo friendly url for the media manager file.
	 *
	 * @param string $mediaFile 
	 * @param string $mediaType 
	 * @param bool $validHtml 
	 *
	 * @return string
	 *
	 */
	public static function getManagedMediaFile($mediaFile, $mediaType, $validHtml = true) {
		$url = '';

		if (rexx::isBackend()) {
			$url = rex_url::backendController() . '?rex_media_type=' . $mediaType . '&rex_media_file=' . $mediaFile;
		} else {
			$url = rexx::getUrlStart() . self::MEDIATYPES_DIR . '/' . $mediaType . '/' . $mediaFile;
		}

		if ($validHtml) {
			return htmlspecialchars($url);
		} else {
			return $url;
		}
	}

	/**
	 * Deprecated: use getManagedMediaFile().
	 *
	 * @param string $mediaFile 
	 * @param string $mediaType 
	 * @param bool $validHtml 
	 *
	 * @return string
	 *
	 */
	public static function getMediaManagerFile($mediaFile, $mediaType, $validHtml = true) {
		return rexx::getManagedMediaFile($mediaFile, $mediaType, $validHtml);
	}

	/**
	 * Returns the last update date of site based on the last article update date. $format is the same param type as in date() function.
	 *
	 * @param string $format  
	 *
	 * @return string
	 *
	 */
	public static function getLastUpdateDate($format = 'd.m.Y') {
		$query =  'SELECT updatedate FROM ' . rexx::getTablePrefix() . 'article WHERE updatedate <> 0 ORDER BY updatedate DESC LIMIT 1';

		$sql = rex_sql::factory();
		$sql->setQuery($query);

		return date($format, strtotime($sql->getValue('updatedate')));
	}

	/**
	 * Converts global settings values to javascript variables.
	 * Example param:
	 * $jsVars = [
	 * 		'cmsSliderPause' => 'glob_slider_pause',
	 * 		'cmsSliderSpeed' => 'glob_slider_speed',
	 * 		'cmsOpenExternalLinksInNewWindow' => 'glob_external_link_new_window'
	 * ];
	 *
	 * @param string[] $jsVars  
	 *
	 * @return string
	 *
	 */
	public static function getGlobalJSVars($jsVars = []) {
		$out = '';

		foreach ($jsVars as $jsVar => $jsValue) {
			$globalVar = rex_global_settings::getDefaultValue($jsValue);
			
			if (is_numeric($globalVar)) {
				$out .= 'var ' . $jsVar . ' = ' . $globalVar . '; ';
			} else {
				$out .= 'var ' . $jsVar . ' = "' . $globalVar . '"; ';
			}
		}

		return $out;
	}

	/**
	 * Explodes and trims a string by given delimiter. Default delimeter is the comma char. Useful for REX_MEDIALIST[] /  REX_LINKLIST[] output.
	 *
	 * @param string $string
	 * @param string $delimiter
	 * @param bool $trim
	 *
	 * @return string
	 *
	 */
	public static function getArrayFromString($string, $delimiter = ',', $trim = true) {
		$array = explode($delimiter, $string);

		if ($trim) {
			$array = array_map('trim', $array);
		}

		return $array;
	}

	/**
	 * Redirects to given article with default 301 status code.
	 *
	 * @param int $id  
	 * @param int $clang  
	 * @param string[] $params
	 * @param int $statusCode
	 *
	 */
	public static function redirectToArticle($id, $clang = null, array $params = [], $statusCode = 301) {
		rexx::redirectToUrl(rexx::getFullUrl($id, $clang, $params, '&'), $statusCode);
	}

	/**
	 * Redirects to given url with default 301 status code.
	 *
	 * @param string[] $url
	 * @param int $statusCode
	 *
	 */
	public static function redirectToUrl($url, $statusCode = 301) {
	    while (@ob_end_clean());
		header('Location: ' . $url, true, $statusCode);
		die();
	}

	/**
	 * Redirects to given url with default 301 status code. Wrapper for rexx::redirectToUrl();
	 *
	 * @param string[] $url
	 * @param int $statusCode
	 *
	 */
	public static function redirect($url, $statusCode = 301) {
		rexx::redirectToUrl($url, $statusCode);
	}

	/**
	 * Gets html attribute based on given attribute values and current article id.
	 * Example param:
	 * $attributeValues = [
	 * 		'1' => 'home'
	 * ];
	 *
	 * @param string[] $attributeValues
	 * @param string $attributeType    
	 *
	 * @return string
	 *
	 */
	public static function getCurrentArticleAttribute($attributeValues, $attributeType = 'id') {
		$curArticleId = rexx::getCurrentArticleId();

		if (isset($attributeValues[$curArticleId])) {
			return ' ' . $attributeType . '="' . $attributeValues[$curArticleId] . '"';
		} else {
			return '';
		}		
	}

	/**
	 * Returns true if given number is odd.
	 *
	 * @param int $number
	 * 
	 * @return bool
	 *
	 */
	public static function isOdd($number) {
		return ($number & 1);
	}

	/**
	 * Returns true if given number is even.
	 *
	 * @param int $number
	 * 
	 * @return bool
	 *
	 */
	public static function isEven($number) {
		return !rexx::isOdd($number);
	}

	/**
	 * Converts a form with fieldsets into tabs :)
	 *
	 * @param string $form
	 * 
	 * @return string
	 *
	 */
	public static function getTabbedForm($form) {
		$html = rexx_simple_html_dom::str_get_html($form);
		$tabs = [];

		if (is_object($html)) {
			$fieldsets = $html->find('fieldset');

			foreach($fieldsets as $fieldset) {
				$legend = $fieldset->find('legend', 0);
				$tabName = $legend->innertext;
				$legend->outertext = '';

				$tabs[] = ['name' => $tabName, 'content' => $fieldset->innertext];
			}
		}

		$tabControl = '';
		$tabIdPrefix = 'tab-';

		$tabControl .= '<div class="mform">';
		$tabControl .= '<ul class="nav nav-tabs rexx-persistent-tabs" role="tablist">';

		for ($i = 0; $i < count($tabs); $i++) {
			if ($i == 0) {
				$class = 'active';
			} else {
				$class = '';
			}

			$tabControl .= '<li role="presentation" class="' . $class . '"><a href="#' . $tabIdPrefix . $i . '" aria-controls="' . $tabIdPrefix . $i . '" role="tab" data-toggle="tab">' . $tabs[$i]['name'] . '</a></li>';
		}

		$tabControl .= '</ul>';
		$tabControl .= '<div class="tab-content">';

		for ($i = 0; $i < count($tabs); $i++) {
			if ($i == 0) {
				$class = 'active';
			} else {
				$class = '';
			}

			$tabControl .= '<div role="tabpanel" class="tab-pane ' . $class . '" id="' . $tabIdPrefix . $i . '">' . $tabs[$i]['content'] . '</div>';
		}

		$tabControl .= '</div>';
		$tabControl .= '</div>';

		return $tabControl;
	}

	/**
	 * Returns an array from a rex value. Wrapper for rex_var::toArray($value);
	 *
	 * @param string $value
	 *
	 * @return array[]
	 *
	 */
	public static function getArrayFromRexValue($value) {
		return rex_var::toArray($value);
	}

	/**
	 * Returns image width of media manager file base on media type.
	 *
	 * @param string $mediaFile
	 * @param string $mediaType
	 *
	 * @return int
	 *
	 */
	public static function getManagedMediaWidth($mediaFile, $mediaType) {
		return rex_media_manager::create($mediaType, $mediaFile)->getMedia()->getImageWidth();
    }

	/**
	 * Returns image height of media manager file base on media type.
	 *
	 * @param string $mediaFile
	 * @param string $mediaType
	 *
	 * @return int
	 *
	 */
	public static function getManagedMediaHeight($mediaFile, $mediaType) {
		return rex_media_manager::create($mediaType, $mediaFile)->getMedia()->getImageHeight();
    }

	/**
	 * Returns a html box for a key/value pair data. Useful for module output in backend.
	 *
	 * @param string $varTitle
	 * @param string $varValue
	 * @param string $icon
	 *
	 * @return string
	 *
	 */
	public static function prettyPrintVar($varTitle, $varValue, $icon = 'fa-info-circle') {
		$out = '';

		if ($icon == '') {
			$iTag = '';
		} else {
			$iTag = '<i class="rex-icon ' . $icon . '"></i> ';
		}

		$out .= '<div class="rexx-pretty-var-box">';
		$out .= $iTag . '<strong>' . $varTitle . ':</strong> ' . $varValue;
		$out .= '</div>';

		return $out;
	}

	/**
	 * Returns true if given slice is valid. Same as rexx::isSliceValid();
	 *
	 * @param rex_article_slice $slice
	 * 
	 * @return bool
	 *
	 */
	public static function isArticleSliceValid($slice) {
		return rexx::isSliceValid($slice);
	}

	/**
	 * Returns true if given slice is valid.
	 *
	 * @param rex_article_slice $slice
	 * 
	 * @return bool
	 *
	 */
	public static function isSliceValid($slice) {
		if ($slice instanceof rex_article_slice) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns true if given article is valid.
	 *
	 * @param rex_article $article
	 * 
	 * @return bool
	 *
	 */
	public static function isArticleValid($article) {
		if ($article instanceof rex_article) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns true if given category is valid.
	 *
	 * @param rex_category $category
	 * 
	 * @return bool
	 *
	 */
	public static function isCategoryValid($category) {
		if ($category instanceof rex_category) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns true if given user is valid.
	 *
	 * @param rex_user $user
	 * 
	 * @return bool
	 *
	 */
	public static function isUserValid($user) {
		if ($user instanceof rex_user) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns a string without p tags. Useful to remove p tags from editor output.
	 *
	 * @param string $string
	 * @param bool $removeParagraphs
	 * 
	 * @return string
	 *
	 */
	public static function getStrippedString($string, $removeParagraphs = true) {
		if ($removeParagraphs) {
			$string = str_replace(['<p>', '</p>'], '', $string);
		}

		return $string;
	}

	/**
	 * Returns true if article with given id is online.
	 *
	 * @param int $articleId
	 * 
	 * @return bool
	 *
	 */
	public static function isArticleOnline($articleId) {
		$article = rexx::getArticle($articleId);

		if (rexx::isArticleValid($article)) {
			return $article->isOnline();
		} else {
			return 'Article with ID = ' . $articleId . ' does not exist!';
		}
	}

	/**
	 * Returns true if current article is online.
	 * 
	 * @return bool
	 *
	 */
	public static function isCurrentArticleOnline() {
		return rexx::isArticleOnline(rexx::getCurrentArticleId());
	}

	/**
	 * Check if previous slice is from same module type. Useful in module outputs to create multi blocks.
	 *
	 * @param int $sliceId
	 * 
	 * @return bool
	 *
	 */
	public static function isFirstSliceOfSameType($sliceId) {
		return rexx::isSliceOfSameType($sliceId, true);
	}
	
	/**
	 * Check if next slice is from same module type. Useful in module outputs to create multi blocks.
	 *
	 * @param int $sliceId
	 * 
	 * @return bool
	 *
	 */
	public static function isLastSliceOfSameType($sliceId) {
		return rexx::isSliceOfSameType($sliceId, false);
	}

	/**
	 * Helper for isFirstSliceOfSameType() and isLastSliceOfSameType().
	 *
	 * @param int $sliceId
	 * @param bool $previous
	 * 
	 * @return bool
	 *
	 */
	protected static function isSliceOfSameType($sliceId, $previous = true) {
		$curSlice = rex_article_slice::getArticleSliceById($sliceId, rexx::getCurrentClangId());

		if (!rexx::isSliceValid($curSlice)) {
			return false;
		}

		if ($previous) {
			$slice = $curSlice->getPreviousSlice();
		} else {
			$slice = $curSlice->getNextSlice();
		}

		if (rexx::isSliceValid($slice)) {
			$sliceStatus = rexx::getSliceStatus($slice->getId());

			if ($sliceStatus == null) {
				$sliceStatus = 1;
			}

			if ($sliceStatus == 1 && $curSlice->getModuleId() == $slice->getModuleId()) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns the status of the slice. Works only if addon like blöcks is installed.
	 *
	 * @param int $sliceId
	 * 
	 * @return int
	 *
	 */
	public static function getSliceStatus($sliceId) {
		$sql = rex_sql::factory();
		$sql->setQuery('SELECT status FROM ' . rexx::getTablePrefix() . 'article_slice WHERE id = ' . $sliceId);

		if ($sql->getRows() > 0) {
			return $sql->getValue('status');
		}

		return null;
	}

	/**
	 * Sets the global locale for php. Then all php strings like month names are in the desired language, use strftime(). 
	 * X-Core calls this automatically.
	 *
	 * @param string $regionCode
	 *
	 */
	public static function setLocale($regionCode = '') {
		if ($regionCode == '') {
			$langCode = rex_clang::getCurrent()->getCode();
			$regionCode = rexx_clang::getPresetValue($langCode, 'region_code');
		}

		if ($regionCode != null) {
			$regionCode = str_replace('-', '_', $regionCode);

			setlocale(LC_ALL, $regionCode . '.UTF-8');
		}
	}

	/**
	 * Returns a download url for the given file. Browser will prompt user to download file with this.
	 *
	 * @param string $file
	 * 
	 * @return string
	 *
	 */
	public static function getDownloadFile($file) {
		return rexx::getUrlStart() . rexx::DOWNLOAD_DIR . '/' . $file;
	}

	/**
	 * Checks if given url is available by the used rewriter. 
	 * Must be called in PACKAGE_INCLUDED or later!
	 *
	 * @param string $url
	 * 
	 * @return bool
	 *
	 */
	public static function isUrlValid($url) {
		$curDomain = rex_yrewrite::getCurrentDomain();

		if (rex_yrewrite::getArticleIdByUrl($curDomain, $url) !== false ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if current url is available by the used rewriter.
	 * Must be called in PACKAGE_INCLUDED or later!
	 * 
	 * @return bool
	 *
	 */
	public static function isCurrentUrlValid() { 
		if (isset($_SERVER['REQUEST_URI'])) {
			$requestUrl = strtok($_SERVER['REQUEST_URI'], '?');
			$requestUrl = ltrim($requestUrl, '/');

			return rexx::isUrlValid($requestUrl);
		} else {
			return false;
		}
	}

	// 
	/**
	 * Removes all subdirs and files recursively.
	 * Keep $securitySwitch = false first to see the path you will delete first in exception!
	 * Be careful with this!
	 *
	 * @param string $dir
	 * @param bool $securitySwitch
	 *
	 */
	public static function removeDirRecursively($dir = null, $securitySwitch = false) {
		if (!$securitySwitch) {
			throw new InvalidArgumentException('rexx::removeDirRecursively(): expecting $securitySwitch to be true. {{{ ATTENTION: this method deletes everything in "' . $dir . '"!!! }}}');
		} elseif ($dir != null && file_exists($dir)) {
			foreach(glob($dir . '/*') as $file) {
				if (is_dir($file)) {
				    self::removeDirRecursively($file, true);
				} else {
				    unlink($file);
				}
			}

			rmdir($dir);
		}
	}

	/**
	 * Checks if $haystack contains $needle.
	 *
	 * @param string $haystack
	 * @param string $needle
	 * 
	 * @return bool
	 *
	 */
	public static function stringContains($haystack, $needle) {
		if (strpos($haystack, $needle) !== false) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks if $haystack starts with $needle.
	 *
	 * @param string $haystack
	 * @param string $needle
	 * 
	 * @return bool
	 *
	 */
	public static function stringStartsWith($haystack, $needle) {
		$length = strlen($needle);

		return (substr($haystack, 0, $length) === $needle);
	}

	/**
	 * Checks if $haystack ends with $needle.
	 *
	 * @param string $haystack
	 * @param string $needle
	 * 
	 * @return bool
	 *
	 */
	public static function stringEndsWith($haystack, $needle) {
		$length = strlen($needle);

		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	/**
	 * Returns true if redaxo is installed in subdir.
	 * 
	 * @return bool
	 *
	 */
	public static function isSubDirInstall() {
		return self::$isSubDirInstall;
	}

	/**
	 * Returns the subdir if redaxo is installed in subdir.
	 * 
	 * @return string
	 *
	 */
	public static function getServerSubDir() {
		return self::$serverSubDir;
	}

	/**
	 * Returns the server url without http:// and can also remove www from host.
	 * 
	 * @param bool $removeWWW
	 * 
	 * @return string
	 *
	 */
	public static function getServerHost($removeWWW = false) {
		$urlParts = parse_url(rexx::getServerUrl());

		if ($removeWWW) {
			return preg_replace('/^www\./', '', $urlParts['host']);
		} else {
			return $urlParts['host'];
		}
	}

	/**
	 * Returns the url of the site start article = home of frontend.
	 * 
	 * @return string
	 *
	 */
	public static function getSiteUrl() {
		return rexx::getUrl(rexx::getSiteStartArticleId(), rexx::getCurrentClangId());
	}

	/**
	 * Returns the category by id.
	 * 
	 * @param int $id
	 * @param int $clangId
	 *
	 * @return rex_category
	 */	
	public static function getCategory($id, $clangId = null) {
		return rex_category::get($id, $clangId);
	}

	/**
	 * Returns the root categories.
	 * 
	 * @param bool $ignoreOfflines
	 * @param int $clangId
	 *
	 * @return rex_category[]
	 */	
	public static function getRootCategories($ignoreOfflines = false, $clangId = null) {
		return rex_category::getRootCategories($ignoreOfflines, $clangId);
	}

	/**
	 * Returns the phone number for use in link with tel: protocol. Strips whitespaces etc and converts + to 00.
	 * 
	 * @param string $phoneNumber
	 * @param bool $convertPrefix
	 * @param string $prefix
	 * @param string $substitute
	 *
	 * @return string
	 */	
	public static function getTelLink($phoneNumber, $convertPrefix = true, $prefix = '+', $substitute = '00') {
		if (rexx::stringStartsWith($phoneNumber, $prefix)) {
			$hasPrefix = true;
		} else {
			$hasPrefix = false;
		}

		// convert prefix if necessary 
		if ($convertPrefix && $hasPrefix) {
			$pos = strpos($phoneNumber, $prefix);
			$phoneNumber = substr_replace($phoneNumber, $substitute, $pos, strlen($prefix));
		}

		// remove all unwanted chars
		$phoneNumber = preg_replace('/\D+/', '', $phoneNumber);

		// put back prefix if necessary 
		if (!$convertPrefix && $hasPrefix) {
			$phoneNumber = '+' . $phoneNumber;
		}

		// prepend tel protocol
		$phoneNumber = 'tel:' . $phoneNumber;

		return $phoneNumber;
	}

	/**
	 * Returns the email link with mailto: at the beginning.
	 * 
	 * @param string $email
	 *
	 * @return string
	 */	
	public static function getEmailLink($email) {
		return 'mailto:' . $email;
	}

	/**
	 * Returns css file with path of resource dir and version string. If less or scss file is given it will be compiled.
	 *
	 * @param string $file
	 * @param string[] $vars
	 * 
	 * @return string
	 *
	 */
	public static function getCSSFile($file, $vars = []) {
		return rexx_resource_includer::getCSSFile($file, $vars);
	}

	/**
	 * Returns js file with path of resource dir and version string.
	 *
	 * @param string $file
	 * 
	 * @return string
	 *
	 */
	public static function getJSFile($file) {
		return rexx_resource_includer::getJSFile($file);
	}

	/**
	 * Returns image file with path of resource dir.
	 *
	 * @param string $file
	 * 
	 * @return string
	 *
	 */
	public static function getImageFile($file) {
		return rexx_resource_includer::getImageFile($file);
	}

	/**
	 * Returns the absolute file with path of the given image of resource dir.
	 *
	 * @param string $file 
	 *
	 * @return string
	 *
	 */
	public static function getAbsoluteImageFile($file) {
		return rexx_resource_includer::getAbsoluteImageFile($file);
	}

	/**
	 * Returns favicon file with path of resource dir.
	 *
	 * @param string $file
	 * 
	 * @return string
	 *
	 */
	public static function getFavIconFile($file) {
		return rexx_resource_includer::getFavIconFile($file);
	}

	/**
	 * Returns file with path of resource dir and version string. If less or scss file is given it will be compiled.
	 *
	 * @param string $fileWithPath
	 * 
	 * @return string
	 *
	 */
	public static function getResourceFile($fileWithPath) {
		return rexx_resource_includer::getResourceFile($fileWithPath);
	}

	/**
	 * Combines multiple css files and returns file with path of resource dir and version string. Also compiles scss and less if necessary.
	 *
	 * @param string $combinedFile
	 * @param string[] $sourceFiles
	 * 
	 * @return string
	 *
	 */
	public static function getCombinedCSSFile($combinedFile, $sourceFiles) {
		return rexx_resource_includer::getCombinedCSSFile($combinedFile, $sourceFiles);
	}

	/**
	 * Combines multiple js files and returns file with path of resource dir and version string.
	 *
	 * @param string $combinedFile
	 * @param string[] $sourceFiles
	 * 
	 * @return string
	 *
	 */
	public static function getCombinedJSFile($combinedFile, $sourceFiles) {
		 return rexx_resource_includer::getCombinedJSFile($combinedFile, $sourceFiles);
	}

	/**
	 * Returns js code as sring from given file.
	 *
	 * @param string $file
	 * @param bool $simpleMinify
	 * 
	 * @return string
	 *
	 */
	public static function getJSCodeFromFile($file, $simpleMinify = true) {
		return rexx_resource_includer::getJSCodeFromFile($file, $simpleMinify);
	}

	/**
	 * Returns global value from global settings addon. If value is empty output will be empty. Same as rex_global_settings::getValue();
	 *
	 * @param string $field
	 * @param int $clangId
	 * @param int $allowEmpty
	 * 
	 * @return string
	 *
	 */
	public static function getGlobalValue($field, $clangId = null, $allowEmpty = true) {
		return rex_global_settings::getValue($field, $clangId, $allowEmpty);
	}

	/**
	 * Returns default global value from global settings addon. If value is empty output will be empty. Same as rex_global_settings::getDefaultValue();
	 *
	 * @param string $field
	 * @param int $allowEmpty
	 * 
	 * @return string
	 *
	 */
	public static function getDefaultGlobalValue($field, $allowEmpty = true) {
		return rex_global_settings::getDefaultValue($field, $allowEmpty);
	}

	/**
	 * Returns global string value from global settings addon. If value is empty output will be a placeholder. Same as rex_global_settings::getString();
	 *
	 * @param string $field
	 * @param int $clangId
	 * @param int $allowEmpty
	 * 
	 * @return string
	 *
	 */
	public static function getString($field, $clangId = null, $allowEmpty = false) {
		return  rex_global_settings::getString($field, $clangId, $allowEmpty);
	}

	/**
	 * Returns default global string value from global settings addon. If value is empty output will be a placeholder. Same as rex_global_settings::getDefaultString();
	 *
	 * @param string $field
	 * @param int $allowEmpty
	 * 
	 * @return string
	 *
	 */
	public static function getDefaultString($field, $allowEmpty = false) {
		return rex_global_settings::getDefaultString($field, $allowEmpty);
	}

	/**
	 * Sets the global preset. Can be used in boot.php of project addon.
	 *
	 * @param string[] $specialChars
	 * @param string[] $specialCharsRewrite
	 *
	 */
	public static function setGlobalLangPreset($specialChars = [], $specialCharsRewrite = []) {
		rexx_clang::setGlobalLangPreset($specialChars, $specialCharsRewrite);
	}

	/**
	 * Modifys a lang preset that already exists. Can be used in boot.php of project addon to modify the existing presets.
	 *
	 * @param string $originalName 
	 * @param string $code
	 * @param string $regionCode
	 * @param string $urlSlug
	 * @param string $hreflang
	 * @param string $dir
	 * @param string[] $specialChars
	 * @param string[] $specialCharsRewrite
	 *
	 */
	public static function setLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite) {
		rexx_clang::setLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite);
	}

	/**
	 * Adds a custom lang preset. Can be used in boot.php of project addon to add a custom preset.
	 *
	 * @param string $originalName 
	 * @param string $code
	 * @param string $regionCode
	 * @param string $urlSlug
	 * @param string $hreflang
	 * @param string $dir
	 * @param string[] $specialChars
	 * @param string[] $specialCharsRewrite
	 *
	 */
	public static function addLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite) {
		rexx_clang::addLangPreset($originalName, $code, $regionCode, $urlSlug, $hreflang, $dir, $specialChars, $specialCharsRewrite);
	}

	/**
	 * Sorts the given array of articles by sort direction and sort type: rexx::ARTICLE_SORT_TYPE_PRIO, rexx::ARTICLE_SORT_TYPE_NAME, rexx::ARTICLE_SORT_TYPE_CREATEDATE, rexx::ARTICLE_SORT_TYPE_UPDATEDATE.
	 *
	 * @param rex_article[] $articles
	 * @param int $sortType
	 * @param bool $sortDirectionAsc
	 * 
	 * @return rex_article[]
	 *
	 */
	public static function sortArticles($articles, $sortType = rexx::ARTICLE_SORT_TYPE_NAME, $sortDirectionAsc = true) {
		// create sort funtion based on sort type
		switch ($sortType) {
			case self::ARTICLE_SORT_TYPE_PRIO:
				$sortFunction = function($article1, $article2) { 
					return rexx::threeWayComparison($article1->getValue('priority'), $article2->getValue('priority'));
				};

				break;
			case self::ARTICLE_SORT_TYPE_NAME:
				$sortFunction = function($article1, $article2) { 
					return rexx::threeWayComparison(strtolower($article1->getName()), strtolower($article2->getName()));
				};

				break;
			case self::ARTICLE_SORT_TYPE_CREATEDATE:
				$sortFunction = function($article1, $article2) { 
					return rexx::threeWayComparison($article1->getValue('createdate'), $article2->getValue('createdate'));
				};

				break;
			case self::ARTICLE_SORT_TYPE_UPDATEDATE:
				$sortFunction = function($article1, $article2) { 
					return rexx::threeWayComparison($article1->getValue('updatedate'), $article2->getValue('updatedate'));
				};

				break;	 	
		}

		// sort
		usort($articles, $sortFunction);

		// reverse array if desc sort direction
		if (!$sortDirectionAsc) {
			$articles = array_reverse($articles);
		}

		// return sorted articles
		return $articles;
	}

	/**
	 * PHP5 Three way comparison. In PHP7 you can use spaceship operator <=>.
	 *
	 * @param int $left
	 * @param int $right
	 *
	 * @return int
	 *
	 */
	public static function threeWayComparison($left, $right) {
		if ($left < $right) {
			return -1;
		} elseif ($left > $right) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Sanitizes a form value by given sanitize type constant like rexx::SANITIZE_TYPE_STRING, rexx::SANITIZE_TYPE_EMAIL, rexx::SANITIZE_TYPE_INT, rexx::SANITIZE_TYPE_URL. Wrapper for rexx_frontend_form::sanitizeFormValue().
	 *
	 * @param string $valueName
	 * @param int $sanitizeType
	 *
	 * @return string
	 *
	 */
	public static function sanitizeFormValue($valueName, $sanitizeType) {
		return rexx_frontend_form::sanitizeFormValue($valueName, $sanitizeType);
	}

	/**
	 * Validates a form value by given validate type constant like rexx::VALIDATE_TYPE_NOT_EMPTY, rexx::VALIDATE_TYPE_EMPTY, rexx::VALIDATE_TYPE_EMAIL, rexx::VALIDATE_TYPE_INT, rexx::VALIDATE_TYPE_URL. Wrapper for rexx_frontend_form::validateFormValue().
	 *
	 * @param string $value
	 * @param int $validateType
	 *
	 * @return bool
	 *
	 */
	public static function validateFormValue($value, $validateType) {
		return rexx_frontend_form::validateFormValue($value, $validateType);
	}

	/**
	 * Helper for getting validate class if $valueName in $valueArray. Wrapper for rexx_frontend_form::getValidateAlertClass().
	 *
	 * @param string $valueName
	 * @param string[] $valueArray
	 *
	 * @return string
	 *
	 */
	public static function getValidateAlertClass($valueName, $valueArray, $validateClass = 'validate-alert') {
		return rexx_frontend_form::getValidateAlertClass($valueName, $valueArray, $validateClass);
	}

	/**
	 * Helper for getting required string with extra required span tag. Wrapper for rexx_frontend_form::getRequiredString().
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
		return rexx_frontend_form::getRequiredString($key, $required, $requiredClass, $requiredContent);
	}

	/**
	 * Helper for getting required placeholder string with extra required content string. Wrapper for rexx_frontend_form::getRequiredPlaceholderString().
	 *
	 * @param string $key
	 * @param bool $required
	 * @param string $requiredContent
	 *
	 * @return string
	 *
	 */
	public static function getRequiredPlaceholderString($key, $required = false, $requiredContent = '*') {
		return rexx_frontend_form::getRequiredPlaceholderString($key, $required, $requiredContent);
	}

	/**
	 * Formats given bytes (e.g. from filesize()) to human readable string like 20,53 MB.
	 *
	 * @param int $bytes
	 *
	 * @return string
	 *
	 */
	public static function formatBytes($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	/**
	 * Like str_replace() but replaces only last occurence. 
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $subject
	 *
	 * @return string
	 *
	 */
	public static function stringReplaceLast($search, $replace, $subject) {
		$pos = strrpos($subject, $search);

		if( $pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}

		return $subject;
	}
}

