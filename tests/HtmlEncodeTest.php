<?php

use PHPUnit\Framework\TestCase;

class HtmlEncodeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once dirname(__DIR__) . '/pub/themes/bismarck/util.php';
    }

    public function testEncodesSpecialCharacters(): void
    {
        $this->assertSame('a&lt;b &amp; &quot;c&quot;', html_encode('a<b & "c"'));
    }

    public function testNullEncodesToEmptyStringWithoutDeprecation(): void
    {
        $messages = [];
        set_error_handler(function ($no, $msg) use (&$messages) {
            $messages[] = $msg;
            return true;
        });
        try {
            $result = html_encode(null);
        } finally {
            restore_error_handler();
        }

        $this->assertSame('', $result);
        $this->assertSame([], $messages);
    }
}
