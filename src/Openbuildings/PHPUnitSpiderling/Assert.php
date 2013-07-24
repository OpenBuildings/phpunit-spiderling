<?php

namespace Openbuildings\PHPUnitSpiderling;

/**
 * Assertions
 *
 * @package    Openbuildings/PHPUNitSpiderling
 * @author     Ivan Kerin
 * @copyright  (c) 2012 OpenBuildings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator('css', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator_Negative('css', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator('field', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator_Negative('field', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator('xpath', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator_Negative('xpath', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator('link', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator_Negative('link', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator('button', $selector, $filters), $message);
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
		return \PHPUnit_Framework_Assert::assertThat($node, new Constraint_Locator_Negative('button', $selector, $filters), $message);
	}

}
