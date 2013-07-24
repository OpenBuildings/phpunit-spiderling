<?php

use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;
use Openbuildings\PHPUnitSpiderling\Constraint_Locator_Negative;

/**
 * Functest_TestsTest 
 *
 * @group functest
 * @group functest.locator
 * @group functest.locator.negative
 * 
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Constraint_Locator_NegativeTest extends Testcase_Spiderling {

	/**
	 * @driver simple
	 */
	public function test_assert_has_css()
	{
		$this->driver()->content(file_get_contents(__DIR__.'/../../../testdata/index.html'));

		$other = $this->getMock('OpenBuildings\Spiderling\Node', array('not_present'), array($this->driver()));
		$node1 = $this->find('#navlink-1');

		$exception = $this->getMock('Openbuildings\Spiderling\Exception_Found', NULL, array(), 'Exception_Found_Test', FALSE);

		$other->expects($this->at(0))
			->method('not_present')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->returnValue(TRUE));

		$other->expects($this->at(1))
			->method('not_present')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->throwException($exception));

		$locator = new Constraint_Locator_Negative('css', '.test', array('filter name' => 'filter'));

		$this->assertTrue($locator->evaluate($other, '', TRUE));

		$this->assertFalse($locator->evaluate($other, '', TRUE));

		$this->assertEquals('does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());
		$this->assertEquals('HTML page does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($other));
		$this->assertEquals('a#navlink-1.navlink does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($node1));
	}
}