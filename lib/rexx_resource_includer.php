<?php

class rexx_resource_includer {
	protected static $cssDir;
	protected static $cssPath;
	protected static $jsDir;
	protected static $jsPath;
	protected static $imagesDir;
	protected static $imagesPath;
	protected static $favIconsDir;
	protected static $favIconsPath;
	protected static $rewriterEnabled;

	public static function init($cssDir, $jsDir, $imagesDir, $favIconsDir) {
		self::setCSSDir($cssDir);
		self::setJSDir($jsDir);
		self::setImagesDir($imagesDir);
		self::setFavIconsDir($favIconsDir);

		self::$rewriterEnabled = true;
	}

	public static function setCSSDir($cssDir) {
		self::$cssDir = self::prepareDir($cssDir);
		self::$cssPath = self::preparePath($cssDir);
	}

	public static function getCSSDir() {
		return self::$cssDir;
	}

	public static function setJSDir($jsDir) {
		self::$jsDir = self::prepareDir($jsDir);
		self::$jsPath = self::preparePath($jsDir);
	}

	public static function getJSDir() {
		return self::$jsDir;
	}

	public static function setImagesDir($imagesDir) {
		self::$imagesDir = self::prepareDir($imagesDir);
		self::$imagesPath = self::preparePath($imagesDir);
	}

	public static function getImagesDir() {
		return self::$imagesDir;
	}

	public static function setFavIconsDir($favIconsDir) {
		self::$favIconsDir = self::prepareDir($favIconsDir);
		self::$favIconsPath = self::preparePath($favIconsDir);
	}

	public static function getFavIconsDir() {
		return self::$favIconsDir;
	}

	public static function getCSSFile($file, $vars = []) {
		if (self::isHttpAddress($file)) {
			return $file;
		} else {
			$fileExtension = self::getFileExtension($file);

			if ($fileExtension == 'scss' || $fileExtension == 'less') {
				$file = self::getCompiledCSSFile($file, $fileExtension, $vars);
			}

			return self::$cssDir . self::getFileWithVersionParam($file, self::$cssPath);
		}
	}

	public static function getJSFile($file) {
		if (self::isHttpAddress($file)) {
			return $file;
		} else {
			return self::$jsDir . self::getFileWithVersionParam($file, self::$jsPath);
		}
	}

	public static function getImageFile($file) {
		return self::$imagesDir . $file;
	}

	public static function getAbsoluteImageFile($file) {
		return realpath(self::$imagesPath) . '/' . $file;
	}

	/* Deprecated */
	public static function getIconFile($file) {
		return self::$favIconsDir . $file;
	}

	public static function getFavIconFile($file) {
		return self::$favIconsDir . $file;
	}

	public static function getResourceFile($fileWithPath, $vars = []) {
		$info = pathinfo($fileWithPath);
		$fileName = $info['basename'];
		$fileExtension = $info['extension'];
		$path = $info['dirname'];

		// compile first
		if ($fileExtension == 'scss' || $fileExtension == 'less') {
			$file = self::getCompiledCSSFile($fileName, $fileExtension, $vars);
		} else {
			$file = $fileName;
		}

		if ($fileExtension == 'css' || $fileExtension == 'scss' || $fileExtension == 'less' || $fileExtension == 'js') {
			return self::prepareDir($path) . self::getFileWithVersionParam($file, rexx::getAbsolutePath($path));
		} else {
			return self::prepareDir($path) . $file;
		}
	}

	public static function getCombinedCSSFile($combinedFile, $sourceFiles) {
		self::combineFiles($combinedFile, self::$cssPath, $sourceFiles);

		return self::getCSSFile($combinedFile);
	}

	public static function getCombinedJSFile($combinedFile, $sourceFiles) {
		self::combineFiles($combinedFile, self::$jsPath, $sourceFiles);

		return self::getJSFile($combinedFile);
	}

	public static function getJSCodeFromTemplate($templateId, $simpleMinify = true) {
		$template = new rex_template($templateId);

		return self::getJSCode($template->getFile(), $simpleMinify);
	}

	public static function getJSCodeFromFile($file, $simpleMinify = true) {
		return self::getJSCode(self::$jsPath . $file, $simpleMinify);
	}

	protected static function prepareDir($dir) {
		return rexx::getUrlStart() . trim($dir, './') . '/';
	}

	protected static function preparePath($dir) {
		return rex_path::frontend() . trim($dir, './') . '/';
	}

	protected static function replaceFileExtension($file, $newExtension) {
		$info = pathinfo($file);

		return $info['filename'] . '.' . $newExtension;
	}

	protected static function getFileExtension($file) {
		$info = pathinfo($file);

		if (isset($info['extension'])) {
			return $info['extension'];
		} else {
			return '';
		}
	}

	protected static function getFileWithVersionParam($file, $path) {
		if (!self::$rewriterEnabled) {
			return $file;
		}

		$file = ltrim($file, "./");
		$path = rtrim($path, "/");
		$mtime = @filemtime($path . '/' . $file); 

		if ($mtime != false) {
			return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
		} else {
			return $file;
		}
	}

	protected static function combineFiles($combinedFile, $filePath, $sourceFiles = []) {
		$combinedFileContent = '';
		$combinedFileWithPath = $filePath . $combinedFile;
		$combinedFileMTime = @filemtime($combinedFileWithPath);
		$doCombine = false;
		$hashString = '';

		// get hash string first
		foreach ($sourceFiles as $file) {
			$hashString .= $file;
		}

		// check if combined file needs to be created
		if ($combinedFileMTime == false) {
			// combined file does not exist
			$doCombine = true;
		} else {
			// check filemtime of source files
			foreach ($sourceFiles as $file) {
				$fileWithPath = $filePath . $file;
				$fileMTime = @filemtime($fileWithPath);

				if ($combinedFileMTime == false || $fileMTime > $combinedFileMTime) {
					// filemtime of one of the source files is newer then of combined file
					$doCombine = true;
					break;
				}
			}

			// check resource id inside combined file (when user changed function arguments for example)
			$fileHandle = @fopen($combinedFileWithPath, 'r');
			$firstLine = '';

			if ($fileHandle != false) {
				$firstLine = fgets($fileHandle);
				fclose($fileHandle);
			}

			$hashStringFromCombinedFile = str_replace('res_id', '', $firstLine);
			$hashStringFromCombinedFile = trim($hashStringFromCombinedFile, " \t\r\n:*/");

			if (self::isValidMd5($hashStringFromCombinedFile) && $hashStringFromCombinedFile != md5($hashString)) {
				$doCombine = true;
			}
		}

		// combine files if necessary
		if ($doCombine) {
			foreach ($sourceFiles as $file) {
				$fileWithPath = $filePath . $file;

				// compile first if scss/less
				$fileExtension = self::getFileExtension($file);

				if ($fileExtension == 'scss' || $fileExtension == 'less') {
					$compiledCSS = self::getCompiledCSSFile($file, $fileExtension);
					$fileWithPath = $filePath . $compiledCSS;
				}
				
				// now combine
				if (file_exists($fileWithPath)) {
					$combinedFileContent .= file_get_contents($fileWithPath);
				} else {
					$combinedFileContent .= '/* file not found: ' . $fileWithPath . ' */';
				}

				$combinedFileContent .=  PHP_EOL . PHP_EOL;
			}

			// add hash
			$combinedFileContent = '/* res_id: ' . md5($hashString) . ' */' . PHP_EOL . PHP_EOL . $combinedFileContent;

			// write combined file
			$fileHandle = @fopen($combinedFileWithPath, 'w');

			if ($fileHandle != false) {
				fwrite($fileHandle, $combinedFileContent);
				fclose($fileHandle);
			}
		}
	}

	protected static function getCompiledCSSFile($sourceFile, $sourceFileType, $vars = []) {
		$cssFile = self::replaceFileExtension($sourceFile, 'css');
		$sourceFileWithPath = self::$cssPath . $sourceFile;
		$cssFileWithPath = self::$cssPath . $cssFile;

		$cssFileMTime = @filemtime($cssFileWithPath);
		$sourceFileMTime = @filemtime($sourceFileWithPath);

		if ($cssFileMTime == false || $sourceFileMTime > $cssFileMTime) {
			// compile scss
			self::compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars);
		}

		// return css file
		return $cssFile;
	}

	protected static function compileCSS($sourceFileWithPath, $cssFileWithPath, $sourceFileType, $vars) {
		if (!file_exists($sourceFileWithPath)) {
			return;
		}
	
		// get content of source file
		$sourceFileContent = file_get_contents($sourceFileWithPath);

		// strip comments out
		$sourceFileContent = self::stripCSSComments($sourceFileContent);

		// get file path
		$path = pathinfo($sourceFileWithPath);

		// compile source file to css
		try {
			if ($sourceFileType == 'scss') {
				// EP for scss string compilation
		        $compiledCSS = rex_extension::registerPoint(new rex_extension_point('XCORE_COMPILE_SCSS', $sourceFileContent, ['vars' => $vars]));

				if ($sourceFileContent == $compiledCSS) {
					require_once(rex_path::addon('xcore', 'lib/scssphp/scss.inc.php'));

					$formatter = new \Leafo\ScssPhp\Formatter\Expanded();
					$formatter->indentChar = "\t";
					$formatter->close = "}" . PHP_EOL;

					$formatter->assignSeparator = ": ";
	
					$scss = new \Leafo\ScssPhp\Compiler();
					$scss->addImportPath($path['dirname']);
					$scss->setFormatter($formatter);
					
					$compiledCSS = $scss->compile($sourceFileContent);
				}
			} else {
				// EP for less string compilation
		        $compiledCSS = rex_extension::registerPoint(new rex_extension_point('XCORE_COMPILE_LESS', $sourceFileContent, ['vars' => $vars, 'path' => $path['dirname']]));
				
				if ($sourceFileContent == $compiledCSS) { 
					$parser = new Less_Parser([
						//'relativeUrls' => false
					]);

					$parser->SetImportDirs([
						realpath($path['dirname']) => ''
					]);

					$parser->parse($sourceFileContent);
					$parser->ModifyVars($vars);

					$compiledCSS = $parser->getCss();
				}
			}
		} catch (Exception $e) {
			echo '" />'; // close tag as we are probably in an open link tag in head section of website 
			echo '<p style="margin: 5px;"><code>';
			echo '<strong>' . strtoupper($sourceFileType) . ' Compile Error:</strong><br/>';
			echo $e->getMessage();
			echo '</code></p>';
			exit;
		}	
		
		// write css
		$fileHandle = fopen($cssFileWithPath, 'w');
		fwrite($fileHandle, $compiledCSS);
		fclose($fileHandle);
	}

	protected static function getJSCode($includeFileWithPath, $simpleMinify = true) {
		$interpretedPhp = '';

		// interpret js as php
		ob_start();

		@include($includeFileWithPath);
		$interpretedPhp = ob_get_contents();

		ob_end_clean();

		if ($simpleMinify) {
			$interpretedPhp = self::simpleJSMinify($interpretedPhp);
		} 

		return $interpretedPhp;
	}

	protected static function simpleJSMinify($code) {
		// strip js comments out
		$minifiedCode = preg_replace('/#.*/', '', preg_replace('#//.*#', '', preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '', ($code))));

		// minified code
		$minifiedCode = trim(preg_replace("/\s+/S", " ", $minifiedCode));

		return $minifiedCode;
	}

	protected static function isHttpAddress($file) {
		if ((strpos($file, 'http') === 0) || strpos($file, '//') === 0) {
			return true;
		} else {
			return false;
		}
	}

	protected static function isValidMd5($md5 = '') {
		return preg_match('/^[a-f0-9]{32}$/', $md5);
	}

	protected static function stripCSSComments($css) {
		return preg_replace('/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/', '', $css);
	}
}


