<?php

namespace Openbuildings\PHPUnitSpiderling\Test\Constraint;

use Openbuildings\PHPUnitSpiderling\TestCase;
use Openbuildings\PHPUnitSpiderling\Constraint\LocatorConstraint;

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
class LocatorConstraintTest extends TestCase {

	/**
	 * @driver simple
	 */
	public function test_assert_has_css()
	{
		$this->driver()->content(file_get_contents(__DIR__.'/../index.html'));

		$other = $this->getMockBuilder('Openbuildings\Spiderling\Node')
			->setMethods(array('find'))
			->setConstructorArgs(array($this->driver()))
			->getMock();
		$node1 = $this->find('#navlink-1');

		$exception = $this->getMockBuilder('Openbuildings\Spiderling\Exception_Notfound')
			->setMockClassName('Exception_Notfound_Test')
			->disableOriginalConstructor()
			->getMock();

		$other->expects($this->at(0))
			->method('find')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->returnValue(TRUE));

		$other->expects($this->at(1))
			->method('find')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->throwException($exception));

		$locator = new LocatorConstraint('css', '.test', array('filter name' => 'filter'));

		$this->assertTrue($locator->evaluate($other, '', TRUE));

		$this->assertFalse($locator->evaluate($other, '', TRUE));

		$this->assertEquals('has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());
		$this->assertEquals('HTML page has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($other));
		$this->assertEquals('a#navlink-1.navlink has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($node1));
	}
}
