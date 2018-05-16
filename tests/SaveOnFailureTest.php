<?php

namespace Openbuildings\PHPUnitSpiderling\Test;

use Openbuildings\PHPUnitSpiderling\SaveOnFailure;
use Openbuildings\PHPUnitSpiderling\TestCase;

class SaveOnFailureTest extends TestCase
{
    public function data_to_absolute_attribute()
    {
        return [
            ['href', '<a href="/test.html?test=1">Test</a>', 'http://example.com', '<a href="http://example.com/test.html?test=1">Test</a>'],
            ['href', '<a href=\'/test.html?test=1\'>Test</a>', 'http://example.com', '<a href=\'http://example.com/test.html?test=1\'>Test</a>'],
            ['href', '<a href=\'/test.html?test=1\'>Test</a>', 'http://example.com/', '<a href=\'http://example.com/test.html?test=1\'>Test</a>'],
            ['src', '<img src="/test.html?test=1"/>', 'http://example.com', '<img src="http://example.com/test.html?test=1"/>'],
            ['src', '<img src="/test.html?test=1"/>', 'http://example.com/', '<img src="http://example.com/test.html?test=1"/>'],
            ['src', '<img src=\'/test.html?test=1\'/>', 'http://example.com', '<img src=\'http://example.com/test.html?test=1\'/>'],
            ['action', '<form action=\'/test.html?test=1\'>', 'http://example.com', '<form action=\'http://example.com/test.html?test=1\'>'],
        ];
    }

    /**
     * @dataProvider data_to_absolute_attribute
     */
    public function test_to_absolute_attribute($attribute, $content, $base_url, $expceted)
    {
        $converted = SaveOnFailure::to_absolute_attribute($attribute, $content, $base_url);

        $this->assertEquals($expceted, $converted);
    }

    /**
     * @driver simple
     */
    public function test_add_error_and_failure()
    {
        $failure = $this->getMockBuilder('PHPUnit\Framework\AssertionFailedError')->getMock();
        $error = $this->getMockBuilder('Exception')->getMock();

        $listener = $this->getMockBuilder('Openbuildings\PHPUnitSpiderling\SaveOnFailure')
            ->setMethods(['save_driver_content'])
            ->setMockClassName('Saveonfailure_Test')
            ->disableOriginalConstructor()
            ->getMock();

        $listener
            ->expects($this->exactly(2))
            ->method('save_driver_content')
            ->with($this->isInstanceOf('Openbuildings\Spiderling\Driver_Simple'), $this->equalTo(self::class.'_test_add_error_and_failure'), $this->equalTo(''));

        // This should not produce a save_driver_content as there is no loaded content
        $listener->addError($this, $error, time());
        $listener->addFailure($this, $failure, time());

        $this->driver()->content('test');

        $listener->addError($this, $error, time());
        $listener->addFailure($this, $failure, time());
    }

    public function test_autocreate_directory()
    {
        $dir = __DIR__.'/test_autocreated_dir';
        $this->assertFalse(is_dir($dir));

        Saveonfailure::autocreate_directory($dir);

        $this->assertTrue(is_dir($dir));
        $this->assertTrue(is_writable($dir));

        rmdir($dir);
    }

    public function test_clear_directory()
    {
        $dir = __DIR__.'/test_clear_dir/';
        mkdir($dir);
        file_put_contents($dir.'test_file.html', 'test');
        file_put_contents($dir.'test_file2.html', 'test');

        Saveonfailure::clear_directory($dir);

        $this->assertFileNotExists($dir.'test_file.html');
        $this->assertFileNotExists($dir.'test_file2.html');

        rmdir($dir);
    }

    /**
     * @driver simple
     */
    public function test_save_driver_content()
    {
        $dir = __DIR__.'/save-on-failure/';
        $listener = new Saveonfailure($dir, 'http://example.com');

        $content = <<<CONTENT
<body>
	<ul class="subnav">
		<li><a class="navlink" id="navlink-1" title="Subpage Title 1" href="/test_functest/subpage1">Subpage 1 <img src="icon1.png" alt="icon 1"/> </a></li>
		<li><a class="navlink" id="navlink-2" title="Subpage Title 2" href="/test_functest/subpage2">Subpage 2 <img src="icon2.png" alt="icon 2"/> </a></li>
	</ul>
</body>

CONTENT;

        $driver = $this->getMockBuilder('Openbuildings\Spiderling\Driver_Simple')->getMock();

        $driver
            ->expects($this->once())
            ->method('javascript_errors')
            ->will($this->returnValue([
                    [
                        'errorMessage' => 'test error',
                        'sourceName' => '',
                        'lineNumber' => 1,
                    ],
                ]));

        $driver
            ->expects($this->once())
            ->method('javascript_messages')
            ->will($this->returnValue(['message1', 'message2']));

        $driver
            ->expects($this->once())
            ->method('content')
            ->will($this->returnValue($content));

        $listener->save_driver_content($driver, 'filename', 'Test Title');

        $this->assertFileEquals(__DIR__.'/expected.html', $dir.'filename.html');
    }
}
