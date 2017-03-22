<?php

namespace Openbuildings\PHPUnitSpiderling;

class SaveOnFailure implements \PHPUnit\Framework\TestListener
{
    /**
     * Convert an attribute strigng from a relative to absolute, by providing a base_url.
     *
     * @param string $attribute name of the attribute, e.g. src, href
     * @param string $content   the string where to do the change
     * @param string $base_url
     *
     * @return string
     */
    public static function to_absolute_attribute($attribute, $content, $base_url = null)
    {
        return preg_replace('/('.$attribute.'=[\'"])\//', '$1'.rtrim($base_url, '/').'/', $content);
    }

    /**
     * Check if a directory does not exist, create it, otherwise check if it is writable.
     *
     * @param string $directory
     *
     * @throws Exception If directory is not writable
     */
    public static function autocreate_directory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!is_writable($directory)) {
            throw new \Exception("Directory \"{$directory}\" is not writable");
        }
    }

    /**
     * Delete all the files from a directory.
     *
     * @param string $directory
     */
    public static function clear_directory($directory)
    {
        foreach (scandir($directory) as $file) {
            if ($file !== '.' and $file !== '..') {
                unlink($directory.$file);
            }
        }
    }

    /**
     * Execute a php script and get the output of that script as a string, optionally pass variables as an associative array to be converted to local variables inside of the file.
     *
     * @param string $filename
     * @param array  $data
     *
     * @return string
     */
    public static function render_file($filename, array $data = [])
    {
        extract($data, EXTR_SKIP);

        ob_start();
        include $filename;

        return ob_get_clean();
    }

    protected $_directory;
    protected $_base_url;

    public function __construct($directory, $base_url)
    {
        if (!$directory) {
            throw new \Exception('You must set a directory to output errors to');
        }

        $this->_directory = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $this->_base_url = $base_url;

        self::autocreate_directory($directory);
        self::clear_directory($directory);
    }

    /**
     * Save the current content of the driver into an html file. Add javascript errors, messages and a title to the html content.
     *
     * @param \Openbuildings\Spiderling\Driver $driver
     * @param string                           $filename
     * @param string                           $title
     */
    public function save_driver_content(\Openbuildings\Spiderling\Driver $driver, $filename, $title)
    {
        $content = $driver->content();

        foreach (['href', 'action', 'src'] as $attribute) {
            $content = self::to_absolute_attribute($attribute, $content, $this->_base_url);
        }

        $testview = self::render_file(__DIR__.'/../assets/error-page.php', [
            'url' => $driver->current_url(),
            'title' => $title,
            'javascript_errors' => $driver->javascript_errors(),
            'javascript_messages' => $driver->javascript_messages(),
        ]);

        $page_content = str_replace('</body>', $testview.'</body>', $content);

        file_put_contents($this->_directory."/$filename.html", $page_content);

        try {
            $driver->screenshot($this->_directory."/$filename.png");
        } catch (\Openbuildings\Spiderling\Exception_Notimplemented $e) {
        }
    }

    /**
     * Implement PHPUnit\Framework\TestListener, save driver content if there was an error.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param \Exception              $exception
     * @param int                     $time
     */
    public function addError(\PHPUnit\Framework\Test $test, \Exception $exception, $time)
    {
        if ($test instanceof TestCase and $test->is_driver_active() and $test->driver()->is_page_active()) {
            $this->save_driver_content(
                $test->driver(),
                get_class($test).'_'.$test->getName(false),
                $exception->getMessage()
            );
        }
    }

    /**
     * Implement PHPUnit\Framework\TestListener, save driver content if there was an error.
     *
     * @param \PHPUnit\Framework\Test                 $test
     * @param \PHPUnit\Framework\AssertionFailedError $failure
     * @param int                                     $time
     */
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $failure, $time)
    {
        if ($test instanceof TestCase and $test->is_driver_active() and $test->driver()->is_page_active()) {
            $this->save_driver_content(
                $test->driver(),
                get_class($test).'_'.$test->getName(false),
                $failure->getMessage()
            );
        }
    }

    // @codeCoverageIgnoreStart
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, $time)
    {
    }

    public function addRiskyTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
    }

    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
    }

    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
    }

    public function startTest(\PHPUnit\Framework\Test $test)
    {
    }

    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
    }

    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    // @codeCoverageIgnoreEnd
}
