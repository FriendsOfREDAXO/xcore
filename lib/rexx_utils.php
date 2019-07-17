<?php

class rexx_utils {
	protected static $is404Status = false;
	protected static $is404CustomPage = false;
	protected static $is404OfflineMode = false;

	public static function set404Status($is404Status = true) {
		self::$is404Status = $is404Status;
	}

	public static function is404Status() {
		return self::$is404Status;
	}

	public static function set404CustomPage($is404CustomPage = true) {
		self::$is404CustomPage = $is404CustomPage;
	}

	public static function is404CustomPage() {
		return self::$is404CustomPage;
	}

	public static function set404OfflineMode($is404OfflineMode = true) {
		self::$is404OfflineMode = $is404OfflineMode;
	}

	public static function is404OfflineMode() {
		return self::$is404OfflineMode;
	}

	public static function get404Page() {
		if (rexx::getLangCode() == 'de') {
			$title = 'Fehler 404 - Seite nicht gefunden';
			$headline = '404';
			$msg1 = 'Die Seite wurde nicht gefunden.';
			$msg2 = 'Zur <a href="' . rexx::getSiteUrl() . '">Startseite</a>.';
		} else {
			$title = '404 error - page not found';
			$headline = '404';
			$msg1 = 'Page not found.';
			$msg2 = 'Goto <a href="' . rexx::getSiteUrl() . '">Startpage</a>.';
		}

		return '
			<!DOCTYPE html>

			<html lang="' . rexx::getLangCode() . '">
			<head>
				<meta charset="utf-8" />
				<title>' . $title . '</title>
				<meta name="robots" content="noindex, follow, noarchive" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<style type="text/css">
					body {
						font-family: Arial, Helvetica, sans-serif;
						font-size: 16px;
						color:  #979797;
						background: #fff;
						text-align: center;
					}

					h1 {
						font-size: 180px;
						line-height: 180px;
						padding-top: 100px;
						margin: 0;
					}
					
					a {
						color:  #979797;
					}

					a:hover {
						text-decoration: none;
					}

					p {
						margin: 0 0 20px 0;
					}

					@media screen and (max-width: 700px) {
						h1 {
							padding-top: 20px;
							font-size: 120px;
							line-height: 120px;
						}
					}
				</style>
			</head>
			<body>
				<h1>' . $headline. '</h1>
				<p>' . $msg1. '</p>
				<p>' . $msg2. '</p>
			</body>
			</html>
			';
	}

	public static function get404OfflineModeMsg() {
		return '
			<!-- X-CORE Offline 404 Mode -->
			<style type="text/css">
				html { 
					margin-top: 30px !important;
				}

				body { 
					position: relative; 
				}

				#rexx-offline-404-frontend-msg { 
					font-family: Arial, sans-serif; 
					font-size: 13px; 
					color: white; 
					background: #4b9ad9; 
					border: 0; 
					position: fixed; 
					left: 0; 
					right: 0; 
					top: 0; 
					padding: 0; 
					text-align: center; 
					z-index: 99999; 
					height: 30px; 
					line-height: 30px; 
					box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.6) !important;
				}

				#rexx-logo { 
					background: #4b9ad9 url("/assets/addons/xcore/images/redaxo-logo_logged_in.svg") no-repeat left top; 
					height: 16px; 
					position: absolute; 
					left: 12px; 
					top: 7px; 
					width: 115px;
				}
			</style>

			<div id="rexx-offline-404-frontend-msg">
				<strong>' . rex_i18n::msg('xcore_offline_404_frontend_msg1') . '</strong> ' . rex_i18n::msg('xcore_offline_404_frontend_msg2') . '<div id="rexx-logo"></div>
			</div>
			<!-- X-CORE Offline 404 Mode -->' .  PHP_EOL;
	}
}
