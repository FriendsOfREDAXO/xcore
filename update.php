<?php

// delete all assets
rex_dir::deleteFiles($this->getAssetsPath(), true);

// copy assets
rex_dir::copy($this->getPath('assets'), $this->getAssetsPath());


