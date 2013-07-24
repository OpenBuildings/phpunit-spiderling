<?php

namespace Openbuildings\PHPUnitSpiderling;

/**
 * Testcase_Functest definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Testcase_Spiderling extends \PHPUnit_Framework_TestCase {
	
	public static $_drivers;

	protected $_driver;
	protected $_driver_type;
	protected $_environment;

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

	public function driver_simple()
	{
		return new \Openbuildings\Spiderling\Driver_Simple();
	}

	public function driver_kohana()
	{
		return new \Openbuildings\Spiderling\Driver_Kohana();
	}

	public function driver_selenium()
	{
		return new \Openbuildings\Spiderling\Driver_Selenium();
	}

	public function driver_phantomjs()
	{
		return new \Openbuildings\Spiderling\Driver_Phantomjs();
	}

	public function driver_type()
	{
		if ($this->_driver_type === NULL) 
		{
			$annotations = $this->getAnnotations();

			$this->_driver_type = isset($annotations['method']['driver'][0]) ? $annotations['method']['driver'][0] : FALSE;
		}

		return $this->_driver_type;
	}

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

	public function is_driver_active()
	{
		return (bool) $this->_driver;
	}

	public function is_environment_active()
	{
		return (bool) $this->_environment;
	}

	public function page()
	{
		$page = $this->driver()->page();
		$page->extension('Openbuildings\PHPUnitSpiderling\Assert');

		return $page;
	}

	public function visit($uri, array $query = array())
	{
		$this->driver()->visit($uri, $query);
		
		return $this;
	}

	public function content()
	{
		return $this->driver()->content();
	}

	public function current_path()
	{
		return $this->driver()->current_path();
	}

	public function current_url()
	{
		return $this->driver()->current_url();
	}

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->page(), $method), $args);
	}
}