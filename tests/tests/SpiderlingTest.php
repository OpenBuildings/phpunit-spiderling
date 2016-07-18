<?php

use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;
use Openbuildings\Spiderling\Driver_Phantomjs_Connection;

/**
 * @package Functest
 * @group   functest
 * @group   functest.spiderling
 */
class SpiderlingTest extends Testcase_Spiderling {

	public function driver_phantomjs()
	{
		return parent::driver_phantomjs()
			->base_url('https://res.cloudinary.com/demo/image/upload/w_150,h_100,c_fill/sample.jpg');
	}

	public function driver_selenium()
	{
		return parent::driver_selenium()
			->base_url('https://res.cloudinary.com/demo/image/upload/w_150,h_100,c_fill/sample.jpg');
	}

	/**
	 * @driver simple
	 */
	public function test_simple()
	{
		$this->assertInstanceOf('Openbuildings\Spiderling\Driver_Simple', $this->driver());

		$this->driver()->content(file_get_contents(__DIR__.'/../testdata/index.html'));

		$this->assertHasCss('#navlink-1', array('text' => 'Subpage 1'));

		$subnav = $this->find('.subnav');

		$link = $subnav->find('a', array('at' => 0));

		$this->assertEquals('Subpage 1', $link->text());

		$this->assertHasField('Enter Email');
	}

	/**
	 * @driver kohana
	 */
	public function test_kohana()
	{
		$this->assertInstanceOf('Openbuildings\Spiderling\Driver_Kohana', $this->driver());

		$this->driver()->content(file_get_contents(__DIR__.'/../testdata/index.html'));

		$this->assertHasCss('#navlink-1', array('text' => 'Subpage 1'));

		$subnav = $this->find('.subnav');

		$link = $subnav->find('a', array('at' => 0));

		$this->assertEquals('Subpage 1', $link->text());

		$this->assertHasField('Enter Email');
	}

	/**
	 * @driver phantomjs
	 */
	public function test_phantomjs()
	{
		$this->assertInstanceOf('Openbuildings\Spiderling\Driver_Phantomjs', $this->driver());

		$this->visit('/index.html');

		$this->assertHasCss('#navlink-1', array('text' => 'Subpage 1'));

		$subnav = $this->find('.subnav');

		$link = $subnav->find('a', array('at' => 0));

		$this->assertContains('Subpage 1', $link->text());

		$this->assertHasField('Enter Email');
	}

	/**
	 * @driver selenium
	 */
	public function test_selenium()
	{
		$this->assertInstanceOf('Openbuildings\Spiderling\Driver_Selenium', $this->driver());

		$this->visit('/index.html');

		$this->assertHasCss('#navlink-1', array('text' => 'Subpage 1'));

		$subnav = $this->find('.subnav');

		$link = $subnav->find('a', array('at' => 0));

		$this->assertContains('Subpage 1', $link->text());

		$this->assertHasField('Enter Email');
	}
}
