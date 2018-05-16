<?php

namespace Openbuildings\PHPUnitSpiderling\Test\Constraint;

use Openbuildings\PHPUnitSpiderling\Constraint\LocatorConstraint;
use Openbuildings\PHPUnitSpiderling\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @group constraint
 */
class LocatorConstraintTest extends TestCase
{
    /**
     * @driver simple
     */
    public function testAssertHasCSS(): void
    {
        $this->driver()->content(file_get_contents(__DIR__.'/../index.html'));

        $other = $this->getMockBuilder('Openbuildings\Spiderling\Node')
            ->setMethods(['find'])
            ->setConstructorArgs([$this->driver()])
            ->getMock();
        $node1 = $this->find('#navlink-1');

        $exception = $this->getMockBuilder('Openbuildings\Spiderling\Exception_Notfound')
            ->setMockClassName('Exception_Notfound_Test')
            ->disableOriginalConstructor()
            ->getMock();

        $other->expects($this->at(0))
            ->method('find')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->returnValue(true));

        $other->expects($this->at(1))
            ->method('find')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->throwException($exception));

        $other->expects($this->at(2))
            ->method('find')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->throwException($exception));

        $locator = new LocatorConstraint('css', '.test', ['filter name' => 'filter']);

        $this->assertTrue($locator->evaluate($other, '', true));

        $this->assertFalse($locator->evaluate($other, '', true));

        $this->assertEquals('has \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());

        try {
            $locator->evaluate($other, '');
        } catch (ExpectationFailedException $expectationFailed) {
            $this->assertEquals(
                'Failed asserting that HTML page has \'css\' selector \'.test\', filter {"filter name":"filter"}.',
                $expectationFailed->getMessage()
            );
        }

        try {
            $locator->evaluate($node1, '');
        } catch (ExpectationFailedException $expectationFailed) {
            $this->assertEquals(
                'Failed asserting that a#navlink-1.navlink has \'css\' selector \'.test\', filter {"filter name":"filter"}.',
                $expectationFailed->getMessage()
            );
        }
    }
}
