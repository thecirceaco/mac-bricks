<?php
/**
 * mac_bricks_enqueue_style() tests.
 *
 * @package mac-bricks
 */

declare(strict_types=1);

namespace MacBricks\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EnqueueStyleTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['mac_bricks_test_styles'] = [];
    }

    /**
     * @return array<string, array{0: string, 1: array<int, string>, 2: array<int, string>}>
     */
    public static function dependencies_naming_the_handle(): array
    {
        return [
            'only its own handle'         => [ 'mac-test', [ 'mac-test' ], [] ],
            'its own handle among others' => [ 'mac-test', [ 'wp-admin', 'mac-test', 'dashicons' ], [ 'wp-admin', 'dashicons' ] ],
            'its own handle twice'        => [ 'mac-test', [ 'mac-test', 'mac-test' ], [] ],
            'the sanitized handle'        => [ 'MAC_Test', [ 'mactest' ], [] ],
        ];
    }

    /**
     * @param array<int, string> $deps
     * @param array<int, string> $expected_deps
     */
    #[DataProvider( 'dependencies_naming_the_handle' )]
    public function test_the_stylesheet_is_enqueued_without_depending_on_itself( string $handle, array $deps, array $expected_deps ): void
    {
        \mac_bricks_enqueue_style( $handle, '/assets/css/admin.css', $deps );

        $this->assertCount( 1, $GLOBALS['mac_bricks_test_styles'] );
        $this->assertSame( $expected_deps, $GLOBALS['mac_bricks_test_styles'][0]['deps'] );
    }
}
