<?php
/**
 * MAC Bricks
 *
 * @package      mac-bricks
 * @author       Circea
 * @copyright    2026 Circea
 * @license      GPL-3.0-or-later
 * @version      1.0.0
 * @since        0.1.0 Added 2026-04-05
 * @link         https://circea.co
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Bootstrap
require_once __DIR__ . '/inc/constants.php';
require_once __DIR__ . '/inc/theme.php';

/**
 * For per-project custom code and options, you can edit:
 *
 * - assets/css/code.css       (for codemirror styles)
 * - assets/css/builder.css    (for builder styles)
 * - assets/css/client.css     (for non-admin styles)
 * - assets/css/admin.css      (for backend styles)
 * - assets/css/main.css       (for frontend styles)
 * - assets/js/main.js         (for frontend scripts)
 * - functions.php             (for custom functions)
 */

/**
 * Allow additional HTML tags in Bricks.
 *
 * https://academy.bricksbuilder.io/article/filter-bricks-allowed_html_tags/
 */
add_filter( 'bricks/allowed_html_tags', function( array $allowed_html_tags ): array {
    return array_values(
        array_unique(
            array_merge( $allowed_html_tags, [ 'time' ] )
        )
    );
} );

/**
 * Echo custom function names.
 *
 * https://academy.bricksbuilder.io/article/filter-bricks-code-echo_function_names/
 */
add_filter( 'bricks/code/echo_function_names', function (): array {
    return [
        '@^mac_',
    ];
} );

/**
 * Custom authentication pages.
 *
 * https://academy.bricksbuilder.io/article/custom-authentication-pages/
 * https://academy.bricksbuilder.io/article/filter-bricks-auth-custom_login_redirect/
 * https://academy.bricksbuilder.io/article/filter-bricks-auth-custom_registration_redirect/
 * https://academy.bricksbuilder.io/article/filter-bricks-auth-custom_lost_password_redirect/
 * https://academy.bricksbuilder.io/article/filter-bricks-auth-custom_reset_password_redirect/
 */
add_filter( 'bricks/auth/custom_registration_redirect', fn(): int => 0 );
add_filter( 'bricks/auth/custom_login_redirect', fn(): int => 0 );
add_filter( 'bricks/auth/custom_lost_password_redirect', fn(): int => 0 );
add_filter( 'bricks/auth/custom_reset_password_redirect', fn(): int => 0 );

// PROJECT
