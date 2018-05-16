<?php

namespace Openbuildings\PHPUnitSpiderling\Test;

use Openbuildings\PHPUnitSpiderling\TestCase;
use Openbuildings\Spiderling\Driver_Kohana;
use Openbuildings\Spiderling\Driver_Phantomjs;
use Openbuildings\Spiderling\Driver_Selenium;
use Openbuildings\Spiderling\Driver_Simple;

class SpiderlingTest extends TestCase
{
    const BASE_URL = 'http://6ca1671dbfe9477b14ce-fabb5009fe9cc97c5f42aa7fac8fcd02.r26.cf3.rackcdn.com';
    const PAGE = '/index.html';

    public function driver_phantomjs(): Driver_Phantomjs
    {
        return parent::driver_phantomjs()->base_url(self::BASE_URL);
    }

    public function driver_selenium(): Driver_Selenium
    {
        return parent::driver_selenium()->base_url(self::BASE_URL);
    }

    /**
     * @driver simple
     */
    public function testSimple(): void
    {
        $this->assertInstanceOf(Driver_Simple::class, $this->driver());
        $this->loadContentInDriver();
        $this->assertContentOnPage();
    }

    /**
     * @driver kohana
     */
    public function testKohana(): void
    {
        $this->assertInstanceOf(Driver_Kohana::class, $this->driver());
        $this->loadContentInDriver();
        $this->assertContentOnPage();
    }

    /**
     * @driver phantomjs
     */
    public function testPhantomjs(): void
    {
        $this->assertInstanceOf(Driver_Phantomjs::class, $this->driver());
        $this->visitPage();
        $this->assertContentOnPage();
    }

    /**
     * @driver selenium
     */
    public function testSelenium(): void
    {
        $this->assertInstanceOf(Driver_Selenium::class, $this->driver());
        $this->visitPage();
        $this->assertContentOnPage();
    }

    private function assertContentOnPage()
    {
        $this->assertHasCss('#navlink-1', ['text' => 'Subpage 1']);

        $subnav = $this->find('.subnav');
        $link = $subnav->find('a', ['at' => 0]);

        $this->assertEquals('Subpage 1', $link->text());
        $this->assertHasField('Enter Email');
    }

    private function loadContentInDriver()
    {
        $this->driver()->content(file_get_contents(__DIR__.self::PAGE));
    }

    private function visitPage()
    {
        $this->visit(self::PAGE);
    }
}
