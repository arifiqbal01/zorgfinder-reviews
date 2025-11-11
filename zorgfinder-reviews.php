<?php
/**
 * Plugin Name: ZorgFinder Reviews
 * Description: Handles provider reviews and ratings for ZorgFinder Core
 * Version: 1.0.0
 * Author: WebArtsy
 * Requires Plugins: zorgfinder-core
 */

if (! defined('ABSPATH')) {
    exit;
}

// load composer if present (safe)
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    // avoid fatal errors — admin notice will tell user to run composer install
    add_action('admin_notices', function () use ($autoload) {
        echo '<div class="notice notice-warning"><p>ZorgFinder Reviews: missing <code>vendor/autoload.php</code>. Run <code>composer install</code> in the plugin folder.</p></div>';
    });
}


// bootstrap files
require_once __DIR__ . '/bootstrap/setup.php';

add_action('init', function () {
    register_block_type(__DIR__ . '/blocks');
});


