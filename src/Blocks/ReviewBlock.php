<?php
namespace ZorgFinder\Reviews\Blocks;

class ReviewBlock
{
    public static function register()
    {
        // Minimal placeholder for server-side registration if desired.
        // Real block would enqueue JS built with @wordpress/scripts & React.
        if (function_exists('register_block_type')) {
            register_block_type('zorgfinder/review-block', [
                'render_callback' => [self::class, 'render']
            ]);
        }
    }

    public static function render($attributes, $content)
    {
        // Simple return — front-end will fetch via REST endpoints
        return '<div class="zorgfinder-review-block">ZorgFinder Review Block (JS implementation recommended)</div>';
    }
}
