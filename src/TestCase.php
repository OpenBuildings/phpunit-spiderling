<?php

namespace Openbuildings\PHPUnitSpiderling;

use Openbuildings\EnvironmentBackup\Environment;
use Openbuildings\EnvironmentBackup\Environment_Group_Globals;
use Openbuildings\EnvironmentBackup\Environment_Group_Server;
use Openbuildings\EnvironmentBackup\Environment_Group_Static;
use Openbuildings\Spiderling\Driver;
use Openbuildings\Spiderling\Driver_Kohana;
use Openbuildings\Spiderling\Driver_Phantomjs;
use Openbuildings\Spiderling\Driver_Selenium;
use Openbuildings\Spiderling\Driver_Simple;
use Openbuildings\Spiderling\Driver_SimpleXML;
use Openbuildings\Spiderling\Page;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base TestCase.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Holds drivers fixtures.
     *
     * @var array
     */
    protected static $_drivers = [];

    /**
     * Current Driver for this testcase.
     *
     * @var Driver
     */
    protected $_driver;

    /**
     * The type of the spiderling driver (kohana, selenium ...).
     *
     * @var string
     */
    protected $_driver_type;

    /**
     * The Environment object making sure you can set env variables and restore them after the test.
     *
     * @var \Openbuildings\EnvironmentBackup\Environment
     */
    protected $_environment;

    /**
     * Restore environment and clear the specific driver if its active.
     */
    protected function tearDown(): void
    {
        if ($this->is_driver_active()) {
            $this->driver()->clear();
        }

        if ($this->is_environment_active()) {
            $this->environment()->restore();
        }

        parent::tearDown();
    }

    /**
     * Return the current driver. This will use driver_simple, driver_kohana ... methods
     * You can override them yourself in order to have custom configs.
     *
     * Drivers are cached as fixtured for the whole testrun and is shared between tests.
     */
    public function driver(): Driver
    {
        if (!$this->_driver) {
            $type = $this->driver_type();

            if (!isset(self::$_drivers[$type])) {
                self::$_drivers[$type] = $this->getDriverFromType($type);
            }

            $this->_driver = self::$_drivers[$type];
        }

        return $this->_driver;
    }

    public function driver_simple(): Driver_Simple
    {
        return new Driver_Simple();
    }

    public function driver_simple_xml(): Driver_SimpleXML
    {
        return new Driver_SimpleXML();
    }

    public function driver_kohana(): Driver_Kohana
    {
        return new Driver_Kohana();
    }

    public function driver_selenium(): Driver_Selenium
    {
        return new Driver_Selenium();
    }

    public function driver_phantomjs(): Driver_Phantomjs
    {
        return new Driver_Phantomjs();
    }

    /**
     * Get the type of the driver for the current test.
     * Use annotations to change the driver type e.g. @driver selenium.
     */
    public function driver_type(): string
    {
        if (null === $this->_driver_type) {
            $annotations = $this->getAnnotations();

            $this->_driver_type = $annotations['method']['driver'][0] ?? false;
        }

        return $this->_driver_type;
    }

    /**
     * Return the environment object that handles setting / restoring env variables.
     */
    public function environment(): Environment
    {
        if (null === $this->_environment) {
            $this->_environment = new Environment([
                'globals' => new Environment_Group_Globals(),
                'server' => new Environment_Group_Server(),
                'static' => new Environment_Group_Static(),
            ]);
        }

        return $this->_environment;
    }

    /**
     * Return true if the driver has been invoked in some way.
     */
    public function is_driver_active(): bool
    {
        return (bool) $this->_driver;
    }

    /**
     * Return true if the environment has been modified / accessed.
     */
    public function is_environment_active(): bool
    {
        return (bool) $this->_environment;
    }

    /**
     * Return the root node of the current page, opened by the driver
     * Extend it with custom assertions from Assert.
     */
    public function page(): Page
    {
        $page = $this->driver()->page();
        $page->extension('Openbuildings\PHPUnitSpiderling\Assert');

        return $page;
    }

    /**
     * All other methods are handled by the root node of the page.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->page(), $method], $args);
    }

    private function getDriverFromType(string $type): Driver
    {
        switch ($type) {
            case 'simple':
                return $this->driver_simple();

            case 'simplexml':
                return $this->driver_simple_xml();

            case 'kohana':
                return $this->driver_kohana();

            case 'phantomjs':
                return $this->driver_phantomjs();

            case 'selenium':
                return $this->driver_selenium();

            default:
                throw new \Exception("Driver '{$type}' does not exist");
        }
    }
}
