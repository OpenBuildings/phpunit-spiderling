<?php

namespace Openbuildings\PHPUnitSpiderling\Test\Constraint;

use Openbuildings\PHPUnitSpiderling\TestCase;
use Openbuildings\PHPUnitSpiderling\Constraint\NegativeLocatorConstraint;

/**
 * @group functest
 * @group functest.locator
 * @group functest.locator.negative
 */
class NegativeLocatorConstraintTest extends TestCase {

	/**
	 * @driver simple
	 */
	public function test_assert_has_css()
	{
		$this->driver()->content(file_get_contents(__DIR__.'/../index.html'));

		$other = $this->getMockBuilder('Openbuildings\Spiderling\Node')
			->setMethods(array('not_present'))
			->setConstructorArgs(array($this->driver()))
			->getMock();
		$node1 = $this->find('#navlink-1');

		$exception = $this->getMockBuilder('Openbuildings\Spiderling\Exception_Found')
			->setMockClassName('Exception_Found_Test')
			->disableOriginalConstructor()
			->getMock();

		$other->expects($this->at(0))
			->method('not_present')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->returnValue(TRUE));

		$other->expects($this->at(1))
			->method('not_present')
			->with($this->equalTo(array('css', '.test', array('filter name' => 'filter'))))
			->will($this->throwException($exception));

		$locator = new NegativeLocatorConstraint('css', '.test', array('filter name' => 'filter'));

		$this->assertTrue($locator->evaluate($other, '', TRUE));

		$this->assertFalse($locator->evaluate($other, '', TRUE));

		$this->assertEquals('does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());
		$this->assertEquals('HTML page does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($other));
		$this->assertEquals('a#navlink-1.navlink does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->failureDescription($node1));
	}
}
