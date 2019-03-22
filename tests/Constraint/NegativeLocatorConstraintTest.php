<?php

namespace Openbuildings\PHPUnitSpiderling\Test\Constraint;

use Openbuildings\PHPUnitSpiderling\Constraint\NegativeLocatorConstraint;
use Openbuildings\PHPUnitSpiderling\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @group constraint
 */
class NegativeLocatorConstraintTest extends TestCase
{
    /**
     * @driver simple
     */
    public function testAssertHasCSS(): void
    {
        $this->driver()->content(file_get_contents(__DIR__.'/../index.html'));

        $other = $this->getMockBuilder('Openbuildings\Spiderling\Node')
            ->setMethods(['not_present'])
            ->setConstructorArgs([$this->driver()])
            ->getMock();
        $node1 = $this->find('#navlink-1');

        $exception = $this->getMockBuilder('Openbuildings\Spiderling\Exception_Found')
            ->setMockClassName('Exception_Found_Test')
            ->disableOriginalConstructor()
            ->getMock();

        $other->expects($this->at(0))
            ->method('not_present')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->returnValue(true));

        $other->expects($this->at(1))
            ->method('not_present')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->throwException($exception));

        $other->expects($this->at(2))
            ->method('not_present')
            ->with($this->equalTo(['css', '.test', ['filter name' => 'filter']]))
            ->will($this->throwException($exception));

        $locator = new NegativeLocatorConstraint('css', '.test', ['filter name' => 'filter']);

        $this->assertTrue($locator->evaluate($other, '', true));

        $this->assertFalse($locator->evaluate($other, '', true));

        $this->assertEquals('does not have \'css\' selector \'.test\', filter {"filter name":"filter"}', $locator->toString());

        try {
            $locator->evaluate($other, '');
        } catch (ExpectationFailedException $expectationFailed) {
            $this->assertEquals(
                'Failed asserting that HTML page does not have \'css\' selector \'.test\', filter {"filter name":"filter"}.',
                $expectationFailed->getMessage()
            );
        }

        try {
            $locator->evaluate($node1, '');
        } catch (ExpectationFailedException $expectationFailed) {
            $this->assertEquals(
                'Failed asserting that HTML page does not have \'css\' selector \'.test\', filter {"filter name":"filter"}.',
                $expectationFailed->getMessage()
            );
        }
    }
}
