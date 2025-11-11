<?php
namespace ZorgFinder\Reviews;

use ZorgFinder\Reviews\Traits\SingletonTrait;
use ZorgFinder\Reviews\API\ReviewsController;

if (! defined('ABSPATH')) exit;

final class Core
{
    use SingletonTrait;

    protected function __construct()
    {
        $this->register_routes();
    }

    private function register_routes(): void
    {
        add_action('rest_api_init', function () {
            (new ReviewsController())->register_routes();
        });
    }
}
