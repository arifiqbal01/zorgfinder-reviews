<?php
/**
 * ZorgFinder Reviews - Setup Bootstrap
 */

if (! defined('ABSPATH')) {
    exit;
}

use ZorgFinder\Reviews\Core as ReviewsCore;

// -----------------------------------------------------------------------------
// Define constants
// -----------------------------------------------------------------------------

if (! defined('ZORGFINDER_REVIEWS_VERSION')) {
    define('ZORGFINDER_REVIEWS_VERSION', '1.0.0');
}

// Base plugin path (resolves correctly regardless of nesting)
if (! defined('ZORGFINDER_REVIEWS_PATH')) {
    define('ZORGFINDER_REVIEWS_PATH', plugin_dir_path(dirname(__FILE__)));
}

// Absolute main plugin file path
if (! defined('ZORGFINDER_REVIEWS_FILE')) {
    define('ZORGFINDER_REVIEWS_FILE', ZORGFINDER_REVIEWS_PATH . 'zorgfinder-reviews.php');
}

// -----------------------------------------------------------------------------
// Initialize Core
// -----------------------------------------------------------------------------

add_action('plugins_loaded', function () {
    // Make sure Composer autoload is active
    if (file_exists(ZORGFINDER_REVIEWS_PATH . 'vendor/autoload.php')) {
        require_once ZORGFINDER_REVIEWS_PATH . 'vendor/autoload.php';
    }

    // Initialize Core singleton if class available
    if (class_exists(ReviewsCore::class)) {
        ReviewsCore::get_instance();
    } else {
        error_log('[ZorgFinder Reviews] Core class not found — autoload may have failed.');
    }
});

// -----------------------------------------------------------------------------
// Activation / Deactivation Hooks
// -----------------------------------------------------------------------------

if (file_exists(__DIR__ . '/Activator.php')) {
    register_activation_hook(
        ZORGFINDER_REVIEWS_FILE,
        ['ZorgFinder\\Reviews\\Bootstrap\\Activator', 'activate']
    );
}

if (file_exists(__DIR__ . '/Deactivator.php')) {
    register_deactivation_hook(
        ZORGFINDER_REVIEWS_FILE,
        ['ZorgFinder\\Reviews\\Bootstrap\\Deactivator', 'deactivate']
    );
}

// Optional: uninstall support if present
if (file_exists(__DIR__ . '/Uninstaller.php')) {
    register_uninstall_hook(
        ZORGFINDER_REVIEWS_FILE,
        ['ZorgFinder\\Reviews\\Bootstrap\\Uninstaller', 'uninstall']
    );
}
