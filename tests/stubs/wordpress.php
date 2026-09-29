<?php
/**
 * Minimal WordPress stubs for the unit tests.
 *
 * Add behavior only when a test needs it.
 *
 * @package mac-bricks
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __DIR__, 2 ) . '/' );
}

if ( ! function_exists( 'add_action' ) ) {
    function add_action( string $hook_name, mixed $callback, int $priority = 10, int $accepted_args = 1 ): true {
        return true;
    }
}

if ( ! function_exists( 'add_filter' ) ) {
    function add_filter( string $hook_name, mixed $callback, int $priority = 10, int $accepted_args = 1 ): true {
        return true;
    }
}

if ( ! function_exists( 'get_stylesheet_directory' ) ) {
    function get_stylesheet_directory(): string {
        return dirname( __DIR__, 2 );
    }
}

if ( ! function_exists( 'get_stylesheet_directory_uri' ) ) {
    function get_stylesheet_directory_uri(): string {
        return 'https://example.test/wp-content/themes/mac-bricks';
    }
}

if ( ! function_exists( 'wp_enqueue_style' ) ) {
    /**
     * Record the call in $GLOBALS['mac_bricks_test_styles'] instead of enqueueing.
     */
    function wp_enqueue_style( string $handle, string $src = '', array $deps = [], string|bool|null $ver = false, string $media = 'all' ): void {
        $GLOBALS['mac_bricks_test_styles'][] = [
            'handle' => $handle,
            'src'    => $src,
            'deps'   => $deps,
            'ver'    => $ver,
            'media'  => $media,
        ];
    }
}
