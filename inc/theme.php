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
    add_action( 'wp_enqueue_scripts', 'mac_bricks_enqueue_frontend_assets', 20 );

    add_filter( 'bricks/builder/codemirror_config', 'mac_bricks_override_codemirror_config' );
    add_filter( 'bricks/builder/save_messages', 'mac_bricks_get_save_messages' );
    add_filter( 'intermediate_image_sizes', 'mac_bricks_filter_image_sizes' );
}

/**
 * Enqueue backend-only styles.
 */
function mac_bricks_enqueue_backend_styles(): void {
    mac_bricks_enqueue_style( 'mac-bricks-admin-styles', '/assets/css/admin.css' );

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
 * Enqueue frontend assets outside the Bricks builder.
 */
function mac_bricks_enqueue_frontend_assets(): void {
    if ( mac_bricks_is_builder() ) {
        return;
    }

    mac_bricks_enqueue_script(
        'mac-bricks-main-scripts',
        '/assets/js/main.js',
        wp_script_is( 'bricks-frontend', 'registered' ) ? [ 'bricks-frontend' ] : [],
        true
    );

    mac_bricks_enqueue_style( 'mac-bricks-main-styles', '/assets/css/main.css' );
}

/**
 * Apply CodeMirror configuration overrides for Bricks.
 *
 * @param array<string, mixed> $config Existing Bricks CodeMirror config.
 * @return array<string, mixed>
 */
function mac_bricks_override_codemirror_config( array $config ): array {
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
 * @param array<int, string> $sizes Registered image sizes.
 * @return array<int, string>
 */
function mac_bricks_filter_image_sizes( array $sizes ): array {
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
 * Enqueue a stylesheet if the file exists.
 *
 * @param string             $handle   WordPress handle.
 * @param string             $rel_path Theme-relative file path.
 * @param array<int, string> $deps     Optional dependencies.
 */
function mac_bricks_enqueue_style( string $handle, string $rel_path, array $deps = [] ): void {
    $file = mac_bricks_asset_path( $rel_path );

    if ( ! is_readable( $file ) ) {
        return;
    }

    wp_enqueue_style(
        $handle,
        mac_bricks_asset_url( $rel_path ),
        $deps,
        (string) filemtime( $file )
    );
}

/**
 * Enqueue a script if the file exists.
 *
 * @param string             $handle    WordPress handle.
 * @param string             $rel_path  Theme-relative file path.
 * @param array<int, string> $deps      Optional dependencies.
 * @param bool               $in_footer Whether to load in the footer.
 */
function mac_bricks_enqueue_script(
    string $handle,
    string $rel_path,
    array $deps = [],
    bool $in_footer = false
): void {
    $file = mac_bricks_asset_path( $rel_path );

    if ( ! is_readable( $file ) ) {
        return;
    }

    wp_enqueue_script(
        $handle,
        mac_bricks_asset_url( $rel_path ),
        $deps,
        (string) filemtime( $file ),
        $in_footer
    );
}

/**
 * Determine whether Bricks builder is active.
 */
function mac_bricks_is_builder(): bool {
    return function_exists( 'bricks_is_builder_main' )
        && bricks_is_builder_main();
}

/**
 * Build a theme-relative asset URL.
 */
function mac_bricks_asset_url( string $rel_path ): string {
    return rtrim( get_stylesheet_directory_uri(), '/' ) . $rel_path;
}

/**
 * Build a theme-relative asset path.
 */
function mac_bricks_asset_path( string $rel_path ): string {
    return rtrim( get_stylesheet_directory(), '/' ) . $rel_path;
}

mac_bricks_register_shared_hooks();
