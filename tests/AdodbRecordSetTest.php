<?php

use PHPUnit\Framework\TestCase;

class AdodbRecordSetTest extends TestCase
{
    public function testAssocFetchModeIsKeptOnTheRecordSet(): void
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
            $db->SetFetchMode(ADODB_FETCH_ASSOC);
            $rs = $db->Execute('SELECT 1 AS one');
        } finally {
            restore_error_handler();
        }

        $this->assertSame('1', (string) $rs->fields['one']);
        $this->assertSame([], array_values(array_filter($messages, function ($m) {
            return strpos($m, 'adodbFetchMode') !== false;
        })));
    }
}
