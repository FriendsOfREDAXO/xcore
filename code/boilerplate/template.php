<!DOCTYPE html>

<html lang="<?php echo rexx::getLangCode(); ?>">
<head>
	<meta charset="utf-8" />
	<base href="<?php echo rexx::getBaseUrl(); ?>" />
	<title><?php echo rexx::getTitle(); ?></title>
	<meta name="description" content="<?php echo rexx::getDescription(); ?>" />
	<meta name="keywords" content="<?php echo rexx::getKeywords(); ?>" />
	<meta name="robots" content="<?php echo rexx::getRobotRules();?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="canonical" href="<?php echo rexx::getCanonicalUrl(); ?>" />
	<link rel="stylesheet" href="<?php echo rexx::getCSSFile('library.css'); ?>" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo rexx::getCSSFile('default.css'); ?>" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo rexx::getCSSFile('print.css'); ?>" type="text/css" media="print" />
	<script type="text/javascript" src="<?php echo rexx::getJSFile('jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo rexx::getJSFile('library.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo rexx::getJSFile('init.js'); ?>"></script>
	<?php echo rexx::getLangTags(); ?>
</head>

<body>
<div id="container">
	<article id="content">
		<?php echo rexx::getCurrentArticleContent(); ?>
	</article>
</div>
</body>
</html>
