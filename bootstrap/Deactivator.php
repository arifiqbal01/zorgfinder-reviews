<?php
namespace ZorgFinder\Reviews\Bootstrap;

if (! defined('ABSPATH')) exit;

class Deactivator
{
    public static function deactivate(): void
    {
        // currently nothing destructive — just flush rewrite rules
        if (function_exists('flush_rewrite_rules')) {
            flush_rewrite_rules();
        }
    }
}
