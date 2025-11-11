<?php
if (! defined('WP_UNINSTALL_PLUGIN')) exit;
require_once __DIR__ . '/vendor/autoload.php';
ZorgFinder\Reviews\Bootstrap\Uninstaller::uninstall();
