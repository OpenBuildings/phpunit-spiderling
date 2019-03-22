<?php

namespace Openbuildings\PHPUnitSpiderling;

use Openbuildings\PHPUnitSpiderling\Constraint\LocatorConstraint;
use Openbuildings\PHPUnitSpiderling\Constraint\NegativeLocatorConstraint;
use PHPUnit\Framework\Assert as PHPUnitAssert;

/**
 * Assertions.
 */
abstract class Assert
{
    /**
     * Assert that an html tag exists inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasCss($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new LocatorConstraint('css', $selector, $filters), $message);
    }

    /**
     * Assert that an html tag does not exist inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasNoCss($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new NegativeLocatorConstraint('css', $selector, $filters), $message);
    }

    /**
     * Assert that an form field exists inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasField($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new LocatorConstraint('field', $selector, $filters), $message);
    }

    /**
     * Assert that an form field does not exist inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasNoField($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new NegativeLocatorConstraint('field', $selector, $filters), $message);
    }

    /**
     * Assert that an html tag exists inside the current tag, matched by xpath.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasXPath($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new LocatorConstraint('xpath', $selector, $filters), $message);
    }

    /**
     * Assert that an html tag does not exist inside the current tag matched by xpath.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasNoXPath($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new NegativeLocatorConstraint('xpath', $selector, $filters), $message);
    }

    /**
     * Assert that an html anchor tag exists inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasLink($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new LocatorConstraint('link', $selector, $filters), $message);
    }

    /**
     * Assert that an html anchor tag does not exist inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasNoLink($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new NegativeLocatorConstraint('link', $selector, $filters), $message);
    }

    /**
     * Assert that an html button tag exists inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasButton($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new LocatorConstraint('button', $selector, $filters), $message);
    }

    /**
     * Assert that an html button tag does not exist inside the current tag.
     *
     * @param string|array $selector
     * @param array        $filters
     * @param string       $message
     */
    public static function assertHasNoButton($node, $selector, array $filters = [], string $message = '')
    {
        PHPUnitAssert::assertThat($node, new NegativeLocatorConstraint('button', $selector, $filters), $message);
    }
}
