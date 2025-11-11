<?php
// bootstrap/setup.php
if (! defined('ABSPATH')) {
    exit;
}

use ZorgFinder\Reviews\Core as ReviewsCore;

define('ZORGFINDER_REVIEWS_VERSION', '1.0.0');
define('ZORGFINDER_REVIEWS_PATH', plugin_dir_path(__DIR__) . 'zorgfinder-reviews/'); // careful because of file location
define('ZORGFINDER_REVIEWS_FILE', dirname(__FILE__) . '/../zorgfinder-reviews.php');

// Initialize plugin after plugins_loaded (safe)
add_action('plugins_loaded', function () {
    // instantiate reviews core
    if (class_exists(ReviewsCore::class)) {
        ReviewsCore::get_instance();
    } else {
        // load class if autoloaded
        // the above class_exists call relies on composer's autoload
    }
});

// register activation/deactivation hooks using bootstrap classes (if autoloaded)
if (file_exists(__DIR__ . '/Activator.php')) {
    register_activation_hook(ZORGFINDER_REVIEWS_FILE, ['ZorgFinder\\Reviews\\Bootstrap\\Activator', 'activate']);
    register_deactivation_hook(ZORGFINDER_REVIEWS_FILE, ['ZorgFinder\\Reviews\\Bootstrap\\Deactivator', 'deactivate']);
    // uninstall handled via uninstall.php or Bootstrapped Uninstaller if present
}
