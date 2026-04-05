<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'MAC_BRICKS_VERSION' ) && function_exists( 'wp_get_theme' ) ) {
    define( 'MAC_BRICKS_VERSION', (string) wp_get_theme()->get( 'Version' ) );
}
