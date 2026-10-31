<?php

use PHPUnit\Framework\TestCase;

class AdodbErrorHandlingTest extends TestCase
{
    public function testFailingStatementReturnsFalseInsteadOfThrowing(): void
    {
        require_once dirname(__DIR__) . '/pub/lib/adodb/adodb.inc.php';
        $db = ADONewConnection('mysqli');
        if (!@$db->Connect('localhost', 'root', 'secretPassword', 'vsp')) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }

        $rs = @$db->Execute('DROP TABLE vsp_table_that_does_not_exist');

        $this->assertFalse($rs);
        $this->assertNotSame(0, $db->ErrorNo());
    }
}
