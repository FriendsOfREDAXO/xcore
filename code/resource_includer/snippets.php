<link rel="stylesheet" href="<?php echo rexx::getCombinedCSSFile("combined.css", array("foo.css", "bar.scss", "batz.less")); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo rexx::getCSSFile("default.css"); ?>" type="text/css" media="screen,print" />
<link rel="stylesheet" href="<?php echo rexx::getCSSFile("theme.scss"); ?>" type="text/css" media="screen,print" />
<link rel="stylesheet" href="<?php echo rexx::getCSSFile("stuff.less", array("color" => "red", "base" => "960px")); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo rexx::getCSSFile("http://fonts.googleapis.com/css?family=Fjalla+One"); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo rexx::getResourceFile("/resources/mediaelement/mediaelementplayer.css"); ?>" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo rexx::getCombinedJSFile("combined.js", array("jquery.magnific-popup.min.js", "jquery.nivo-slider.min.js")); ?>"></script>
<script type="text/javascript" src="<?php echo rexx::getJSFile("http://codeorigin.jquery.com/jquery-2.0.3.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo rexx::getJSFile("init.js"); ?>"></script>
<script type="text/javascript" src="<?php echo rexx::getResourceFile("/resources/mediaelement/mediaelement-and-player.min.js"); ?>"></script>

<link rel="shortcut icon" href="<?php echo rexx::getFavIconFile("favicon.ico"); ?>" />

<img src="<?php echo rexx::getImageFile("logo.png"); ?>" />
