<?php 

namespace Openbuildings\PHPUnitSpiderling;


/**
 * Phpunit_Saveonfailure definition
 *
 * @package Functest
 * @author Ivan Kerin
 * @copyright  (c) 2011-2013 Despark Ltd.
 */
class Saveonfailure implements \PHPUnit_Framework_TestListener {

	public static function to_absolute_attribute($attribute, $content, $base_url = NULL)
	{
		return preg_replace('/('.$attribute.'=[\'"])\//', '$1'.rtrim($base_url, '/').'/', $content);
	}

	public static function autocreate_directory($directory)
	{
		if ( ! file_exists($directory))
		{
			mkdir($directory, 0777, TRUE);
		}

		if ( ! is_writable($directory))
			throw new \Exception("Directory \"{$directory}\" is not writable");
	}

	public static function clear_directory($directory)
	{
		foreach (scandir($directory) as $file) 
		{
			if ($file !== '.' AND $file !== '..')
			{
				unlink($directory.$file);
			}
		}
	}

	public function render_file($filename, array $data)
	{
		extract($data, EXTR_SKIP);

		ob_start();
		include $filename;
		return ob_get_clean();
	}

	protected $_directory;
	protected $_base_url;

	function __construct($directory, $base_url)
	{
		if ( ! $directory)
			throw new Exception('You must set a directory to output errors to');

		$this->_directory = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		$this->_base_url = $base_url;

		self::autocreate_directory($directory);
		self::clear_directory($directory);
	}

	public function save_driver_content(\Openbuildings\Spiderling\Driver $driver, $filename, $title)
	{
		$content = $driver->content();

		foreach (array('href', 'action', 'src') as $attribute) 
		{
			$content = self::to_absolute_attribute($attribute, $content, $this->_base_url);
		}

		$testview = self::render_file(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'error-page.php', array(
			'url' => $driver->current_url(), 
			'title' => $title, 
			'javascript_errors' => $driver->javascript_errors(),
			'javascript_messages' => $driver->javascript_messages(),
		));

		$page_content = str_replace('</body>', $testview.'</body>', $content);

		file_put_contents($this->_directory."/$filename.html", $page_content);
	}

	public function addError(\PHPUnit_Framework_Test $test, \Exception $exception, $time)
	{
		if ($test instanceof Testcase_Spiderling AND $test->is_driver_active() AND $test->driver()->is_page_active())
		{
			$this->save_driver_content(
				$test->driver(), 
				get_class($test).'_'.$test->getName(FALSE), 
				$exception->getMessage()
			);
		}
	}

	public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $failure, $time)
	{
		if ($test instanceof Testcase_Spiderling AND $test->is_driver_active() AND $test->driver()->is_page_active())
		{

			$this->save_driver_content(
				$test->driver(), 
				get_class($test).'_'.$test->getName(FALSE), 
				$failure->getMessage()
			);
		}
	}

	// @codeCoverageIgnoreStart
	public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}
	public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) {}
	public function startTest(\PHPUnit_Framework_Test $test) {}
	public function endTest(\PHPUnit_Framework_Test $test, $time) {}
	public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {}
	public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {}
	// @codeCoverageIgnoreEnd
}