<?php
declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register shared child-theme hooks.
 */
function mac_bricks_register_shared_hooks(): void {
    add_action( 'admin_enqueue_scripts', 'mac_bricks_enqueue_backend_styles' );
    add_action( 'wp_enqueue_scripts', 'mac_bricks_enqueue_builder_styles' );

    add_filter( 'bricks/builder/codemirror_config', 'mac_bricks_override_codemirror_config' );
    add_filter( 'bricks/builder/save_messages', 'mac_bricks_get_save_messages' );
    add_filter( 'intermediate_image_sizes', 'mac_bricks_filter_image_sizes' );
}

/**
 * Enqueue backend-only styles.
 */
function mac_bricks_enqueue_backend_styles(): void {
    mac_bricks_enqueue_style( 'mac-bricks-admin-styles', '/assets/css/admin.css' );

    // The role check is intentional: client roles can carry admin-like
    // capabilities. client.css is cosmetic only, not access control.
    if ( ! current_user_can( 'administrator' ) ) {
        mac_bricks_enqueue_style( 'mac-bricks-client-styles', '/assets/css/client.css' );
    }
}

/**
 * Enqueue Bricks builder styles.
 */
function mac_bricks_enqueue_builder_styles(): void {
    if ( ! mac_bricks_is_builder() ) {
        return;
    }

    mac_bricks_enqueue_style( 'mac-bricks-code-styles', '/assets/css/code.css' );
    mac_bricks_enqueue_style( 'mac-bricks-builder-styles', '/assets/css/builder.css' );
}

/**
 * Apply CodeMirror configuration overrides for Bricks.
 *
 * @param mixed $config Existing Bricks CodeMirror config.
 * @return array<string, mixed> Empty when $config is not an array.
 */
function mac_bricks_override_codemirror_config( mixed $config = [] ): array {
    if ( ! is_array( $config ) ) {
        return [];
    }

    return array_merge(
        $config,
        [
            'theme'             => 'mac',
            'lineNumbers'       => true,
            'tabSize'           => 2,
            'indentUnit'        => 2,
            'indentWithTabs'    => false,
            'autoCloseBrackets' => true,
            'matchBrackets'     => true,
        ]
    );
}

/**
 * Return custom Bricks builder save messages.
 *
 * @return array<int, string>
 */
function mac_bricks_get_save_messages(): array {
    return [
        'Saved-ish!',
        'Eww, a vibe coder.',
        'Not tested in Safari!',
        'Built with love and bugs!',
        'Debug me like one of your French girls!',
        'Bricks? More like Legos.',
        'Save now, regret never.',
        'Looks fine on my screen.',
        'Technically correct.',
        'Probably saved!',
        'The illusion of safety!',
        'Bugs? Never heard of \'em.',
        'Still sane, coder?',
        'GG EZ!',
        'Et tu, Firefox?',
        'Div centered.',
        'Works on my machine.',
        'Needs more divs.',
        '!important? Bold move.',
        'Commit message: "misc changes".',
        'One does not simply refactor.',
        'Please don\'t inspect element.',
        'Exit code 1, emotional damage.',
        'I use Bricks, BTW.',
        'Fedora moment.',
        'The logs know.',
        'Bricked. Literally.',
        '404: Motivation not found.',
        'Silence is golden.',
        'OP didn\'t read the docs.',
        'Achievement unlocked: Saved!',
        'Level 99 in bug fixing.',
        '+1 to UX.',
        'Saving… but at what cost?',
        'Bug? That\'s a feature.',
        'YAWN RIGHT NOW !!!',
        'Debugging is my cardio.',
        'Saved! Now go touch grass.',
        'Another one for the changelog.',
        'The cake is a lie.',
        'delete_option( \'feelings\' );',
        'May your divs always be centered.',
        'CSS wizardry in progress.',
        '404: Coffee not found.',
        'Uncaught exception: Life.',
        'Alt + F4 to save.',
        'If you can read this, you\'re too close.',
        'Bricks and giggles.',
        '*Opens Google* forgets why...',
        'Live. Laugh. Loop.',
        'Will it break? Maybe.',
        'Trust me, I\'m lying.',
        'Accidentally thriving.',
        'Fixed 3, made 4 new ones.',
        'Noice.',
        'Not a chump afterall.',
        'Not broke, just pre-rich.',
        '*5 AM* ok, just one more section...',
        'The mitochondria is the powerhouse of the cell.',
        'I put the "pro" in procrastination.',
        'I have a keyboard, and I\'m not afraid to use it.',
        'Don\'t follow me, I\'m lost too.',
        'I fear no bug. Except that one.',
        'Zero context, full confidence.',
        'The vibes are unstable.',
        'Winging it since line one.',
        '"It\'s responsive" — a bold claim.',
        'That\'s not a bug, that\'s a plot twist.',
        'UI so clean it exfoliates.',
        'Who gave this code a license?',
        'Oh, boy...',
        '+10 aura.',
        'Here we go again...',
        'This is fine.',
        '*12 hours later*',
        'Make WordPress great again!',
        'If not bug, why bug-shaped?',
        'Just a guy with a keyboard.',
        'Dream big, achieve nothing.',
        'Bricks and stones may break my bones.',
        'Never gonna give you up ...',
        '... Never gonna let you down.',
        'echo \'Saved!\';',
        '$PHP = lambo_money;',
        'Minimum input, maximum output $$$.',
        'PHP devs don\'t run tests, we run the streets.',
    ];
}

/**
 * Remove selected Bricks image sizes.
 *
 * @param mixed $sizes Registered image sizes.
 * @return array<int, string> Empty when $sizes is not an array.
 */
function mac_bricks_filter_image_sizes( mixed $sizes = [] ): array {
    if ( ! is_array( $sizes ) ) {
        return [];
    }

    return array_values(
        array_diff(
            $sizes,
            [
                'bricks_large',
                'bricks_large_16x9',
                'bricks_large_square',
                'bricks_medium',
                'bricks_medium_square',
            ]
        )
    );
}

/**
 * Enqueue a stylesheet from the theme's assets folder.
 *
 * Does nothing unless the handle keeps at least one character after
 * sanitizing and the path resolves to a readable .css file inside assets/.
 *
 * @param mixed $handle   WordPress handle, reduced to a-z, 0-9 and dashes.
 * @param mixed $rel_path Theme-relative file path, e.g. '/assets/css/admin.css'.
 * @param mixed $deps     Optional dependency handles; only the strings of an array are used,
 *                        and the stylesheet's own handle is left out.
 */
function mac_bricks_enqueue_style( mixed $handle = '', mixed $rel_path = '', mixed $deps = [] ): void {
    $handle   = is_scalar( $handle ) ? (string) $handle : '';
    $rel_path = is_scalar( $rel_path ) ? (string) $rel_path : '';
    $deps     = is_array( $deps ) ? array_values( array_filter( $deps, 'is_string' ) ) : [];

    // The handle ends up in the <link> tag's id and in style_loader_tag filters.
    $handle = (string) preg_replace( '/[^a-z0-9-]/', '', strtolower( $handle ) );
    $file   = mac_bricks_asset_path( $rel_path );

    // WordPress never finishes resolving a stylesheet that depends on itself:
    // it recurses until PHP runs out of memory.
    $deps = array_values( array_diff( $deps, [ $handle ] ) );

    if (
        '' === $handle
        || '' === $file
        || 'css' !== strtolower( pathinfo( $file, PATHINFO_EXTENSION ) )
        || ! is_readable( $file )
    ) {
        return;
    }

    // Unescaped on purpose: WordPress escapes the URL when it prints the tag,
    // and would read the # of an escaped & or ' as the start of a fragment.
    $src = rtrim( get_stylesheet_directory_uri(), '/' ) . '/' . ltrim( $rel_path, '/' );

    wp_enqueue_style( $handle, $src, $deps, (string) filemtime( $file ) );
}

/**
 * Determine whether Bricks builder is active.
 */
function mac_bricks_is_builder(): bool {
    return function_exists( 'bricks_is_builder_main' )
        && bricks_is_builder_main();
}

/**
 * Build the URL of a file in the theme's assets folder.
 *
 * @param mixed $rel_path Theme-relative file path, e.g. '/assets/css/admin.css'.
 * @return string URL escaped with esc_url(), or an empty string when
 *                mac_bricks_asset_path() rejects the path.
 */
function mac_bricks_asset_url( mixed $rel_path = '' ): string {
    $rel_path = is_scalar( $rel_path ) ? (string) $rel_path : '';

    if ( '' === mac_bricks_asset_path( $rel_path ) ) {
        return '';
    }

    return esc_url( rtrim( get_stylesheet_directory_uri(), '/' ) . '/' . ltrim( $rel_path, '/' ) );
}

/**
 * Resolve a theme-relative path to a file in the theme's assets folder.
 *
 * Paths containing '..' or a NUL byte are refused before the file system is
 * touched. Other paths are resolved with realpath() and must lead to a file
 * inside assets/, also after following symlinks.
 *
 * The result is a file-system path and is returned unescaped, since escaping
 * could change it; it can only be the real path of a file inside assets/.
 *
 * @param mixed $rel_path Theme-relative file path, e.g. '/assets/css/admin.css'.
 * @return string Real path of the file, or an empty string for any other path.
 */
function mac_bricks_asset_path( mixed $rel_path = '' ): string {
    $rel_path = is_scalar( $rel_path ) ? (string) $rel_path : '';

    if ( '' === $rel_path || str_contains( $rel_path, '..' ) || str_contains( $rel_path, "\0" ) ) {
        return '';
    }

    $theme  = rtrim( get_stylesheet_directory(), '/' );
    $assets = realpath( $theme . '/assets' );
    $file   = realpath( $theme . '/' . ltrim( $rel_path, '/' ) );

    if (
        false === $assets
        || false === $file
        || ! str_starts_with( $file, $assets . DIRECTORY_SEPARATOR )
        || ! is_file( $file )
    ) {
        return '';
    }

    return $file;
}

mac_bricks_register_shared_hooks();
