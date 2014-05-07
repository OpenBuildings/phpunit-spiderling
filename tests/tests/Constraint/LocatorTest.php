<?php

use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;
use Openbuildings\PHPUnitSpiderling\Constraint_Locator;

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
class Constraint_LocatorTest extends Testcase_Spiderling {

	/**
	 * @driver simple
	 */
	public function test_assert_has_css()
	{
		$this->driver()->content(file_get_contents(__DIR__.'/../../testdata/index.html'));

		$other = $this->getMock('OpenBuildings\Spiderling\Node', array('find'), array($this->driver()));
		$node1 = $this->find('#navlink-1');

		$exception = $this->getMock('Openbuildings\Spiderling\Exception_Notfound', NULL, array(), 'Exception_Notfound_Test', FALSE);

		$other->expects($this->at(0))
			->method('find')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->returnValue(TRUE));

		$other->expects($this->at(1))
			->method('find')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->throwException($exception));

		$locator = new Constraint_Locator('css', '.test', array('filter name' => 'filter'));

		$this->assertTrue($locator->evaluate($other, '', TRUE));

		$this->assertFalse($locator->evaluate($other, '', TRUE));

		$this->assertEquals('has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());
		$this->assertEquals('HTML page has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($other));
		$this->assertEquals('a#navlink-1.navlink has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($node1));
	}
}
