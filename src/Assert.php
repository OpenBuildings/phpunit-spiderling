<?php

namespace Openbuildings\PHPUnitSpiderling;

/**
 * Assertions
 */
abstract class Assert {

	/**
	 * Assert that an html tag exists inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasCss($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\LocatorConstraint('css', $selector, $filters), $message);
	}

	/**
	 * Assert that an html tag does not exist inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasNoCss($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\NegativeLocatorConstraint('css', $selector, $filters), $message);
	}

	/**
	 * Assert that an form field exists inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasField($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\LocatorConstraint('field', $selector, $filters), $message);
	}

	/**
	 * Assert that an form field does not exist inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasNoField($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\NegativeLocatorConstraint('field', $selector, $filters), $message);
	}

	/**
	 * Assert that an html tag exists inside the current tag, matched by xpath
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasXPath($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\LocatorConstraint('xpath', $selector, $filters), $message);
	}

	/**
	 * Assert that an html tag does not exist inside the current tag matched by xpath
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasNoXPath($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\NegativeLocatorConstraint('xpath', $selector, $filters), $message);
	}

	/**
	 * Assert that an html anchor tag exists inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasLink($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\LocatorConstraint('link', $selector, $filters), $message);
	}

	/**
	 * Assert that an html anchor tag does not exist inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasNoLink($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\NegativeLocatorConstraint('link', $selector, $filters), $message);
	}

	/**
	 * Assert that an html button tag exists inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasButton($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\LocatorConstraint('button', $selector, $filters), $message);
	}

	/**
	 * Assert that an html button tag does not exist inside the current tag
	 * @param  string|array   $selector
	 * @param  array          $filters
	 * @param  string         $message
	 * @return Functest_Node  $this
	 */
	public static function assertHasNoButton($node, $selector, array $filters = array(), $message = NULL)
	{
		return \PHPUnit\Framework\Assert::assertThat($node, new Constraint\NegativeLocatorConstraint('button', $selector, $filters), $message);
	}

}
