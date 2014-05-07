# PHPUnit Spiderling

[![Build Status](https://travis-ci.org/OpenBuildings/phpunit-spiderling.png?branch=master)](https://travis-ci.org/OpenBuildings/phpunit-spiderling)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/OpenBuildings/phpunit-spiderling/badges/quality-score.png?s=9a1986ae84df9ddd029a3ad41d9030d4f4263453)](https://scrutinizer-ci.com/g/OpenBuildings/phpunit-spiderling/)
[![Code Coverage](https://scrutinizer-ci.com/g/OpenBuildings/phpunit-spiderling/badges/coverage.png?s=37d447d31d3dc3b4129e6e7c79a33c192f71c322)](https://scrutinizer-ci.com/g/OpenBuildings/phpunit-spiderling/)
[![Latest Stable Version](https://poser.pugx.org/openbuildings/phpunit-spiderling/v/stable.png)](https://packagist.org/packages/openbuildings/phpunit-spiderling)

Heavily inspired by capybara [capybara](https://github.com/jnicklas/capybara). Using the [spiderling](https://github.com/OpenBuildings/spiderling) package to the fullest. It gives you the ability to quickly write integration test with powerful DSL and choose between different drivers with different combinations of features and performance - e.g. selenium, phanomjs or raw php with curl.

Example Test:

```php
use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;

class SpiderlingTest extends Testcase_Spiderling {

	/**
	 * @driver phantomjs
	 */
	public function test_sample()
	{
		$this
			->visit('http://example.com/index.html')
			->assertHasCss('#navlink-1', array('text' => 'Subpage 1'), 'Should have a navigation link')
			->click_button('Edit')
			->assertHasCss('h1', array('text' => 'Edit Record'), 'Should be on the edit page of a record')
			->fill_in('Name', 'New name')
			->click_button('Save')
			->assertHasCss('.notification', array('text' => 'Successfull edit'), 'Should have successfully performed the edit');
	}
}
```

## Spiderling

[Spiderling](https://github.com/OpenBuildings/spiderling) has a fluid DSL that its best you get familiar with. The testcase has all the methods of Openbuildings\Spiderling\Page so you can use them directly along with some additional assertions.

## The Testcase

To use phpunit spiderling you need to write your test as "extends Testcase_Spiderling" - it will give you all of the feature as methods of the class.

### Assertions

The custom assertions are:

- ``assertHasCss($selector, array $filters = array(), $message = NULL)``
- ``assertHasNoCss($selector, array $filters = array(), $message = NULL)``
- ``assertHasField($selector, array $filters = array(), $message = NULL)``
- ``assertHasNoField($selector, array $filters = array(), $message = NULL)``
- ``assertHasXPath($selector, array $filters = array(), $message = NULL)``
- ``assertHasNoXPath($selector, array $filters = array(), $message = NULL)``
- ``assertHasLink($selector, array $filters = array(), $message = NULL)``
- ``assertHasNoLink($selector, array $filters = array(), $message = NULL)``
- ``assertHasButton($selector, array $filters = array(), $message = NULL)``
- ``assertHasNoButton($selector, array $filters = array(), $message = NULL)``

All of them assert that an element is on the page (or is not) by using a specific locator type and filters. Also they are actually an extension of Openbuildings\Spiderling\Page so they are available to all the nested nodes (and are asserting only in the context of the node)

For example:

```php
use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;

class SpiderlingTest extends Testcase_Spiderling {

	public function test_sample()
	{
		$this
			->visit('http://example.com/index.html')
			->find('form#big')
				// This assertion checks only the form
				->assertHasField('Name')
			->end();
	}
}
```

### Environment

You can modify the environment only for a specific test by using the ``->environment()`` method. It returns a [Environment Backup](https://github.com/OpenBuildings/environment-backup) object, and whatever you set it to will be restored at the end of the test.

Here's an example usage:

```php
use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;

class SpiderlingTest extends Testcase_Spiderling {

	public function test_sample()
	{
		$this
			->environment()
				->backup_and_set(array(
					'SomeClass::$variable' => 'new value',
					'REMOTE_HOST' => 'example.com',
				));

		$this
			->visit('http://example.com/index.html')
			->assertHasField('Name');
	}
}
```

### Switching drivers

PHPUnit Spiderling uses the PHP Annotations to set up which driver to use fore each test. Heres how you do that:

```php
use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;

class SpiderlingTest extends Testcase_Spiderling {

	/**
	 * @driver selenium
	 */
	public function test_sample()
	{
		// ...
	}


	/**
	 * @driver phantomjs
	 */
	public function test_sample_2()
	{
		// ...
	}
}
```

You can have different drivers for each test, the available ones are: ``simple``, ``kohana``, ``selenium`` and ``phantomjs`` - where the default driver is ``simple``. Each driver is loaded with the default configuration, but you can change it by modifying the appropriate method that loads the driver

- driver_simple() - will return Driver_Simple object
- driver_kohana() - will return Driver_Kohana object
- driver_selenium() - will return Driver_Selenium object
- driver_phantomjs() - will return Driver_Phantomjs object

It is recommended that you create a base TestCase class in your tests if you want to extend these methods and have your relevant class extend that class for example if we had selenium running on a different server than localhost we might do something like this:

```php
use Openbuildings\PHPUnitSpiderling\Testcase_Spiderling;
use Openbuildings\Spiderling\Driver_Selenium;
use Openbuildings\Spiderling\Driver_Selenium_Connection;

abstract class TestCase_Selenium extends Testcase_Spiderling {

	public function driver_selenium()
	{
		$connection = new Driver_Selenium_Connection('http://selenium-server.example.com/web/hub/');
		return new Driver_Selenium($connection);
	}
}
```

And then you would:

```php

class IntegrationTest extends TestCase_Selenium {

	/**
	 * @driver selenium
	 */
	public function test_sample()
	{
		// ...
	}
}
```

## Save on failure

There is a special testcase listener class included that saves the state of the test when there is a failure, and saves it as an html page for ease referance later. This is the ``Saveonfailure`` class. In order to use it, you'll need to modify your phpunit.xml like this:

```xml
<!-- It is important to have composer autoloading. you can have a different bootstrap file, but this is the standard and easiest way to handle it. -->
<phpunit bootstrap="vendor/autoload.php">
	<listeners>
		<listener class="Openbuildings\PHPUnitSpiderling\Saveonfailure" file="vendor/openbuildings/phpunit-spiderling/src/Openbuildings/PHPUnitSpiderling/Saveonfailure.php">
			<arguments>
				<!-- This is the folder where all the html snapshots will be placed -->
				<string>application/logs/functest</string>
				<!-- This is the base url which will be prefixed in fron of all relative assets, so that the page is loaded properly. Optional -->
				<string>http://test.clippings.dev</string>
			</arguments>
		</listener>
	</listeners>
</phpunit>
```

## License

Copyright (c) 2012-2013, OpenBuildings Ltd. Developed by Ivan Kerin as part of [clippings.com](http://clippings.com)

Under BSD-3-Clause license, read LICENSE file.
