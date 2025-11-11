<?php
namespace ZorgFinder\Reviews\Database\Migrations;

class CreateReviewsTable
{
    public function up(): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'zf_reviews';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            provider_id BIGINT(20) UNSIGNED NOT NULL,
            user_id BIGINT(20) UNSIGNED DEFAULT NULL,
            rating TINYINT(1) NOT NULL DEFAULT 5,
            comment TEXT DEFAULT NULL,
            approved TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            KEY provider_id (provider_id),
            KEY user_id (user_id),
            KEY approved (approved)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
