<?php

// includes
require_once(rex_path::addon('xcore', 'functions/functions.php')); // contains out() function which is very useful for debugging purpose

// init main class
rexx::init();

// activate router
if (rexx::isFrontend()) {
	$controller = new rexx_router();
	$controller->route();
}

// smart redirects
if (rex_config::get('xcore', 'smart_redirects') == 1 && rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function() {	
		if (!rexx::isCurrentUrlValid() && isset($_SERVER['REQUEST_URI'])) {
			$trimmedRequestUrl = str_replace('.html', '', trim($_SERVER['REQUEST_URI'], '/'));
			$newUrl = $trimmedRequestUrl . rexx::getUrlEnding();

			if (rexx::isUrlValid($newUrl)) {
				rexx::redirect(rexx::getUrlStart() . $newUrl);
			}
		}
	}, rex_extension::LATE);
}

// set correct locale
rexx::setLocale();

// add x-core customs styles
if (rexx::isBackend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		rex_view::addJsFile($this->getAssetsUrl('js/main.js'));
		rex_view::addCssFile($this->getAssetsUrl('css/backend.css'));

		if (rex_config::get('xcore', 'xcore_styles') == 1) {
			rex_view::addCssFile($this->getAssetsUrl('css/xcore.css'));
		}
	}, rex_extension::LATE);


	rex_extension::register('PAGE_BODY_ATTR', function (\rex_extension_point $ep) {
	    $subject = $ep->getSubject();
	
		if (rex_config::get('xcore', 'xcore_styles') == 1 && rex_config::get('be_style/customizer', 'labelcolor') == '#43a047') {
		    $subject['class'][] = 'rexx-customizer-is-green';
		}

		if (rex_config::get('xcore', 'xcore_styles') == 1 && rex_config::get('be_style/customizer', 'codemirror') == '1') {
		    $subject['class'][] = 'rexx-customizer-codemirror-available';
		}

		if (rex_config::get('xcore', 'show_meta_frontend_link') == 1) {
		    $subject['class'][] = 'rexx-has-meta-frontend-link';
		}

	    $ep->setSubject($subject);
	});
}

// yrewrite: add own schema
rex_yrewrite::setScheme(new rexx_yrewrite_scheme());

// backend hacks throught output filter ep
if (rexx::isBackend()) {
	rex_extension::register('OUTPUT_FILTER', function($ep) {
		$subject = $ep->getSubject();

		// logo anti flicker patch
		if (rex::getUser() instanceof rex_user) {
			$newLogo = 'redaxo-logo_logged_in.svg';
		} else {
			$newLogo = 'redaxo-logo_logged_out.svg';
	
		}
	
		$subject = str_replace('../assets/core/redaxo-logo.svg', $this->getAssetsUrl('images/' . $newLogo), $subject);

		// setup msg in addon install page
		if (rex_be_controller::getCurrentPagePart(1) == 'packages') {
			$subject = str_replace(rex_i18n::msg('addon_installed', 'xcore'), rex_i18n::msg('addon_installed', 'xcore') . ' <br/>' . rex_i18n::rawMsg('xcore_addon_installed'), $subject);
		}

		// restore normal bootstrap tab style for mform
		if (rex_config::get('xcore', 'xcore_styles') == 1) {
			$subject = str_replace('mform-tabs rex-page-nav', '', $subject);
		}
		
		return $subject;
	}, rex_extension::LATE);
}

// send headers
if (rexx::isFrontend()) {
	// this is only for current article
	rex_extension::register('OUTPUT_FILTER', function() {
		header('X-UA-Compatible: IE=Edge');	// html tag not necessary anymore with this
	});

	// fix headers for media manager images, necessary for some 1und1 servers and others
	if (rex_get('rex_media_file') != '' && rex_get('rex_media_type') != '') {
		header('Cache-Control: max-age=604800'); // 1 week
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
	}
}

// show offline 404 message for frontend user
if (rex_config::get('xcore', 'offline_404_mode') == 1 && rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		$article = rexx::getCurrentArticle();

		if (!$article->isOnline() && $article->getId() != rex_article::getNotfoundArticleId()) {
			if (rex_backend_login::createUser()) {
				rex_extension::register('OUTPUT_FILTER', function($ep) {
					$subject = $ep->getSubject();

					return str_replace('</body>', rexx_utils::get404OfflineModeMsg() . '</body>', $subject);
				});
			} else {
				rex_addon::get('structure')->setProperty('article_id', rexx::getNotfoundArticleId());

				rexx_utils::set404Status();
				rexx_utils::set404OfflineMode();
			}
		}
	}, rex_extension::LATE);
}

// correct redaxo behaviour and send 404 if sitestartarticle = notfoundarticle
if (rexx::isFrontend()) {
	rex_extension::register('PACKAGES_INCLUDED', function(rex_extension_point $ep) {
		if (rexx::getSiteStartArticleId() == rexx::getNotfoundArticleId()) {
			if (!rexx::isCurrentUrlValid()) {
				rexx_utils::set404Status();
				rexx_utils::set404CustomPage();
			} else {
				if (rexx_utils::is404OfflineMode()) {
					rexx_utils::set404Status();
					rexx_utils::set404CustomPage();	
				}
			}
		}
	}, rex_extension::LATE);
}

// send 404 header and page if necessary
rex_extension::register('PACKAGES_INCLUDED', function() {
	if (rexx::isFrontend() && rexx_utils::is404Status()) {
		rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
			rex_response::setStatus(rex_response::HTTP_NOT_FOUND);

			if (rexx_utils::is404CustomPage()) {
				$ep->setSubject(rexx_utils::get404Page());
			}
		}, rex_extension::LATE);
	}
}, rex_extension::LATE);

// add docs to api_docs addon if available
if (rexx::isBackend()) {
	rex_extension::register('API_DOCS', function(rex_extension_point $ep) {
		$subject = $ep->getSubject();

		if (isset($subject['api']['links'])) {
			$subject['api']['links'][] = [
				'title' => rex_i18n::msg('xcore_api_docs_title'),
				'description' => rex_i18n::msg('xcore_api_docs_description'),
				'href' => rex_url::backendPage('xcore/rexx_api'),
				'open_in_new_window' => false
			];
		}

		$ep->setSubject($subject);
	});
}

if (rexx::isBackend() && $this->getConfig('show_meta_frontend_link') == 1) {
	rex_extension::register('META_NAVI', function(rex_extension_point $ep) {
		$subject = $ep->getSubject();

		$subject[] = '<li><a href="' . rex_url::frontend() . '" target="_blank"><i class="rex-icon fa-globe"></i> ' . rex_i18n::msg('xcore_goto_website') . '</a></li>';

		$ep->setSubject($subject);
	});
}

// xcore included ep (TODO: put everything in PACKAGES_INCLUDED so this can make sense finally ;))
rex_extension::registerPoint(new rex_extension_point('XCORE_INCLUDED'));

