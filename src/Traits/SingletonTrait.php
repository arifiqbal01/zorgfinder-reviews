<?php
namespace ZorgFinder\Reviews\Traits;

/**
 * Generic Singleton pattern trait (PHP 8+ safe)
 */
trait SingletonTrait
{
    private static $instance = null;

    final public static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    // Prevent direct instantiation
    final protected function __construct() {}

    // Prevent cloning
    final protected function __clone() {}

    // PHP 8+ requires __wakeup to be public if defined
    final public function __wakeup(): void
    {
        // Intentionally empty — prevents unserialization
    }
}
