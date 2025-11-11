<?php
namespace ZorgFinder\Reviews\Bootstrap;

use ZorgFinder\Reviews\Database\Migrations\CreateReviewsTable;
use ZorgFinder\Reviews\Database\Migrations\MigrationRunner as MR;

if (! defined('ABSPATH')) exit;

class Activator
{
    public static function activate(): void
    {
        // run this plugin's migrations (safe even if core is missing)
        try {
            $runner = new MR();
            $runner->run();
        } catch (\Throwable $e) {
            error_log('[ZorgFinder Reviews Activation Error] ' . $e->getMessage());
        }

        // store install time/version
        if (! get_option('zorgfinder_reviews_installed')) {
            add_option('zorgfinder_reviews_installed', current_time('mysql'));
        }
        update_option('zorgfinder_reviews_version', ZORGFINDER_REVIEWS_VERSION);

        // flush rewrite rules for REST
        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules();
        }
    }
}
