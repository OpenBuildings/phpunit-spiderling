<?php

namespace Openbuildings\PHPUnitSpiderling\Test;

use Openbuildings\PHPUnitSpiderling\Assert;
use Openbuildings\PHPUnitSpiderling\TestCase;

/**
 * @group assert
 */
class AssertTest extends TestCase
{
    /**
     * @driver simple
     */
    public function testAssertHasCSS(): void
    {
        $this->driver()->content(file_get_contents(__DIR__.'/index.html'));

        Assert::assertHasCss($this->page(), '#navlink-1', ['text' => 'Subpage 1']);
        Assert::assertHasNoCss($this->page(), '#navlink-111', ['text' => 'Subpage 1']);

        Assert::assertHasField($this->page(), 'Enter Email', ['visible' => true]);
        Assert::assertHasNoField($this->page(), 'Enter Email Not Exists', ['visible' => true]);

        Assert::assertHasXPath($this->page(), '//a[@id="navlink-1"]', ['text' => 'Subpage 1']);
        Assert::assertHasNoXPath($this->page(), '//a[@class="navlink-12"]', ['text' => 'Subpage 1']);

        Assert::assertHasLink($this->page(), 'Subpage 1', ['text' => 'Subpage 1']);
        Assert::assertHasNoLink($this->page(), 'Subpage 1 111', ['text' => 'Subpage 1']);

        Assert::assertHasButton($this->page(), 'Submit Button', ['text' => 'Submit Button']);
        Assert::assertHasNoButton($this->page(), 'Submit Button11', ['text' => 'Submit Button']);
    }
}
