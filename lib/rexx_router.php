<?php

class rexx_router {
	public function route() {
		$func = rex_get('rexx_func', 'string');

		switch ($func) {
			case 'download':
				if (rex_config::get('xcore', 'allow_downloads') == 1) {
					$file = rex_get('rexx_file', 'string');
					$file = $this->sanitizeFile($file);

					$this->sendDownloadFile($file);
				}
			break;
		}
	}

	private function sendDownloadFile($file) {
		@error_reporting(0);
		@ini_set('display_errors', 0);

		$fileWithPath = rexx::getAbsoluteMediaFile($file);

		if (file_exists($fileWithPath) && is_file($fileWithPath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $file);
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Link: <' . rexx::getFullMediaUrl($file) . '>; rel="canonical"');
			header('Content-Length: ' . filesize($fileWithPath));

			ob_clean();
			flush();

			readfile($fileWithPath);

			exit;
		}
	}

	private function sanitizeFile($file) {
		$sanitizeFile = strtolower(preg_replace("/[^a-zA-Z0-9.\-\$\+]/", "_", $file));
		$sanitizeFile = urlencode(basename($sanitizeFile));

		return $file;
	}
}

