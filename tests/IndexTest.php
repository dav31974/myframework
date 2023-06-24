<?php
// framework/test.php
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testHello()
    {
        $_GET['name'] = 'David';

        ob_start();
        include 'index.php';
        $content = ob_get_clean();

        $this->assertEquals('Hello David', $content);
    }
}
