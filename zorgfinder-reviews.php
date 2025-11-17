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

// -------------------------------------------------------
// Load Composer autoloader FIRST
// -------------------------------------------------------
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    add_action('admin_notices', function () use ($autoload) {
        echo '<div class="notice notice-warning"><p>ZorgFinder Reviews: missing <code>vendor/autoload.php</code>. Run <code>composer install</code> in the plugin folder.</p></div>';
    });
}

// -------------------------------------------------------
// Bootstrap (this handles setup, constants, hooks)
// -------------------------------------------------------
require_once __DIR__ . '/bootstrap/setup.php';

// -------------------------------------------------------
// Gutenberg Blocks Registration
// -------------------------------------------------------
add_action('init', function () {
    register_block_type(__DIR__ . '/blocks');
});

// Ensure Deactivator class is loaded for deactivation hook
require_once __DIR__ . '/bootstrap/Deactivator.php';

register_deactivation_hook(
    __FILE__,
    ['ZorgFinder\\Reviews\\Bootstrap\\Deactivator', 'deactivate']
);
