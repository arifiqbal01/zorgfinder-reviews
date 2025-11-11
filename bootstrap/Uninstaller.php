<?php
namespace ZorgFinder\Reviews\Bootstrap;

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'zf_reviews';
$wpdb->query("DROP TABLE IF EXISTS $table");

delete_option('zorgfinder_reviews_installed');
delete_option('zorgfinder_reviews_version');
