<?php
namespace ZorgFinder\Reviews;

use ZorgFinder\Reviews\Traits\SingletonTrait;
use ZorgFinder\Reviews\API\ReviewsController;
// remove BlockRegistrar unless you re-add it later
if (! defined('ABSPATH')) exit;

/**
 * Main bootstrap class for the ZorgFinder Reviews plugin.
 */
final class Core
{
    use SingletonTrait;

    /**
     * Boot the plugin.
     */
    protected function __construct()
    {
        $this->register_routes();
        $this->register_blocks();
    }

    /**
     * Register REST API routes.
     */
    private function register_routes(): void
    {
        add_action('rest_api_init', function () {
            (new ReviewsController())->register_routes();
        });
    }

    /**
     * Register Gutenberg blocks if any exist.
     */
    private function register_blocks(): void
    {
        // If you already have a BlockRegistrar class:
        if (class_exists('\ZorgFinder\Reviews\Blocks\BlockRegistrar')) {
            \ZorgFinder\Reviews\Blocks\BlockRegistrar::register_all();
        }
    }
}
