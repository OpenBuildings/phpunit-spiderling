<?php

namespace Openbuildings\PHPUnitSpiderling\Test;

use Openbuildings\PHPUnitSpiderling\TestCase;
use Openbuildings\PHPUnitSpiderling\Assert;

/**
 * @group assert
 */
class AssertTest extends TestCase {

	/**
	 * @driver simple
	 */
	public function test_assert_has_css()
	{
		$this->driver()->content(file_get_contents(__DIR__.'/index.html'));

		Assert::assertHasCss($this->page(), '#navlink-1', array('text' => 'Subpage 1'));
		Assert::assertHasNoCss($this->page(), '#navlink-111', array('text' => 'Subpage 1'));

		Assert::assertHasField($this->page(), 'Enter Email', array('visible' => TRUE));
		Assert::assertHasNoField($this->page(), 'Enter Email Not Exists', array('visible' => TRUE));

		Assert::assertHasXPath($this->page(), '//a[@id="navlink-1"]', array('text' => 'Subpage 1'));
		Assert::assertHasNoXPath($this->page(), '//a[@class="navlink-12"]', array('text' => 'Subpage 1'));

		Assert::assertHasLink($this->page(), 'Subpage 1', array('text' => 'Subpage 1'));
		Assert::assertHasNoLink($this->page(), 'Subpage 1 111', array('text' => 'Subpage 1'));

		Assert::assertHasButton($this->page(), 'Submit Button', array('text' => 'Submit Button'));
		Assert::assertHasNoButton($this->page(), 'Submit Button11', array('text' => 'Submit Button'));
	}
}
