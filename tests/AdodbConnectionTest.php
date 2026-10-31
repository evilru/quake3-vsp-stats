<?php

use PHPUnit\Framework\TestCase;

class AdodbConnectionTest extends TestCase
{
    public function testConnectExposesDatabaseNameWithoutDynamicProperty(): void
    {
        $messages = [];
        set_error_handler(function ($no, $msg) use (&$messages) {
            $messages[] = $msg;
            return true;
        });
        try {
            require_once dirname(__DIR__) . '/pub/lib/adodb/adodb.inc.php';
            $db = ADONewConnection('mysqli');
            if (!@$db->Connect('localhost', 'root', 'secretPassword', 'vsp')) {
                $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
            }
        } finally {
            restore_error_handler();
        }

        $this->assertSame('vsp', $db->databaseName);
        $this->assertSame([], array_values(array_filter($messages, function ($m) {
            return strpos($m, 'databaseName') !== false;
        })));
    }
}
