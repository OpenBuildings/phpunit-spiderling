<?php

namespace Openbuildings\PHPUnitSpiderling;

/**
 * Base Testcase
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Testcase_Spiderling extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Holds drivers fixtures
	 * @var array
	 */
	protected static $_drivers = array();

	/**
	 * Current Driver for this testcase
	 * @var Openbuildings\Spiderling\Driver
	 */
	protected $_driver;

	/**
	 * The type of the spiderling driver (kohana, selenium ...)
	 * @var string
	 */
	protected $_driver_type;

	/**
	 * The Environment object making sure you can set env variables and restore them after the test 
	 * @var Openbuildings\EnvironmentBackup\Environment
	 */
	protected $_environment;

	/**
	 * Restore environment and clear the specific driver if its active
	 */
	public function tearDown()
	{
		if ($this->is_driver_active())
		{
			$this->driver()->clear();
		}

		if ($this->is_environment_active())
		{
			$this->environment()->restore();
		}
		
		parent::tearDown();
	}

	/**
	 * Return the current driver. This will use driver_simple, driver_kohana ... methods 
	 * You can override them yourself in order to have custom configs
	 *
	 * Drivers are cached as fixtured for the whole testrun and is shared between tests.
	 * @return Openbuildings\Spiderling\Driver
	 */
	public function driver()
	{
		if ( ! $this->_driver)
		{
			$type = $this->driver_type();

			if (isset(self::$_drivers[$type]))
			{
				$this->_driver = self::$_drivers[$type];
			}
			else
			{
				switch ($type) 
				{
					case 'simple':
						$driver = $this->driver_simple();
					break;

					case 'kohana':
						$driver = $this->driver_kohana();
					break;
					
					case 'phantomjs':
						$driver = $this->driver_phantomjs();
					break;

					case 'selenium':
						$driver = $this->driver_selenium();
					break;

					default:
						throw new Exception("Driver :driver does not exist {$name}");
				}
				$this->_driver = self::$_drivers[$type] = $driver;
			}
		}

		return $this->_driver;
	}

	/**
	 * Return Openbuildings\Spiderling\Driver_Simple
	 * override this to configure
	 * 
	 * @return Openbuildings\Spiderling\Driver_Simple
	 */
	public function driver_simple()
	{
		return new \Openbuildings\Spiderling\Driver_Simple();
	}

	/**
	 * Return Openbuildings\Spiderling\Driver_Kohana
	 * override this to configure
	 * 
	 * @return Openbuildings\Spiderling\Driver_Kohana
	 */
	public function driver_kohana()
	{
		return new \Openbuildings\Spiderling\Driver_Kohana();
	}

	/**
	 * Return Openbuildings\Spiderling\Driver_Selenium
	 * override this to configure
	 * 
	 * @return Openbuildings\Spiderling\Driver_Selenium
	 */
	public function driver_selenium()
	{
		return new \Openbuildings\Spiderling\Driver_Selenium();
	}

	/**
	 * Return Openbuildings\Spiderling\Driver_Phantomjs
	 * override this to configure
	 * 
	 * @return Openbuildings\Spiderling\Driver_Phantomjs
	 */
	public function driver_phantomjs()
	{
		return new \Openbuildings\Spiderling\Driver_Phantomjs();
	}

	/**
	 * Get the type of the driver for the current test. 
	 * Use annotations to change the driver type e.g. @driver selenium
	 * 
	 * @return string
	 */
	public function driver_type()
	{
		if ($this->_driver_type === NULL) 
		{
			$annotations = $this->getAnnotations();

			$this->_driver_type = isset($annotations['method']['driver'][0]) ? $annotations['method']['driver'][0] : FALSE;
		}

		return $this->_driver_type;
	}

	/**
	 * return the environment object that handles setting / restoring env variables
	 * @return Openbuildings\EnvrionmentBackup\Envrionment
	 */
	public function environment()
	{
		if ($this->_environment === NULL)
		{
			$this->_environment = new \Openbuildings\EnvironmentBackup\Environment(array(
				'globals' => new \Openbuildings\EnvironmentBackup\Environment_Group_Globals(),
				'server' => new \Openbuildings\EnvironmentBackup\Environment_Group_Server(),
				'static' => new \Openbuildings\EnvironmentBackup\Environment_Group_Static(),
				'config' => new \Openbuildings\EnvironmentBackup\Environment_Group_Config(),
			));
		}
		return $this->_environment;
	}

	/**
	 * Return true if the driver has been invoked in some way
	 * @return boolean 
	 */
	public function is_driver_active()
	{
		return (bool) $this->_driver;
	}

	/**
	 * Return true if the environment has been modified / accessed
	 * @return boolean 
	 */
	public function is_environment_active()
	{
		return (bool) $this->_environment;
	}

	/**
	 * Return the root node of the current page, opened by the driver
	 * Extend it with custom assertions from Assert
	 * @return Openbuildings\Spiderling\Node 
	 */
	public function page()
	{
		$page = $this->driver()->page();
		$page->extension('Openbuildings\PHPUnitSpiderling\Assert');

		return $page;
	}

	/**
	 * Initiate a visit with the currently selected driver
	 * @param  string $uri   
	 * @param  array  $query 
	 * @return $this
	 */
	public function visit($uri, array $query = array())
	{
		$this->driver()->visit($uri, $query);
		
		return $this;
	}

	/**
	 * Return the content of the last request from the currently selected driver
	 * @return string 
	 */
	public function content()
	{
		return $this->driver()->content();
	}

	/**
	 * Return the current browser url without the domain
	 * @return string 
	 */
	public function current_path()
	{
		return $this->driver()->current_path();
	}

	/**
	 * Return the current url
	 * @return string
	 */
	public function current_url()
	{
		return $this->driver()->current_url();
	}

	/**
	 * All other methods are handled by the root node of the page
	 * @param  string $method 
	 * @param  array $args   
	 * @return mixed         
	 */
	public function __call($method, $args)
	{
		return call_user_func_array(array($this->page(), $method), $args);
	}
}