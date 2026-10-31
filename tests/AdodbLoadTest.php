<?php

use PHPUnit\Framework\TestCase;

class AdodbLoadTest extends TestCase
{
    /** Loads ADOdb in a fresh PHP process and returns its deprecation messages plus a formatted date. */
    private function loadAdodb(string $extra = ''): array
    {
        $code = 'set_error_handler(function ($n, $m) { echo "MSG:", $m, "\n"; return true; });'
            . 'require ' . var_export(dirname(__DIR__) . '/pub/lib/adodb/adodb.inc.php', true) . ';'
            . 'echo "DATE:", adodb_date("Y-m-d", 86400 * 365), "\n";' . $extra;
        exec(escapeshellarg(PHP_BINARY) . ' -d error_reporting=-1 -r ' . escapeshellarg($code) . ' 2>&1', $lines);
        return $lines;
    }

    public function testAdodbDateWorksAndLoadingHasNoNonCanonicalCast(): void
    {
        $lines = $this->loadAdodb();

        $this->assertContains('DATE:1971-01-01', $lines);
        $this->assertSame([], array_values(preg_grep('/Non-canonical cast/', $lines)));
    }

    public function testRecordSetIsIterableAndLoadingHasNoReturnTypeDeprecation(): void
    {
        $lines = $this->loadAdodb(
            '$db = ADONewConnection("mysqli");'
            . 'if (!@$db->Connect("localhost", "root", "secretPassword", "vsp")) { echo "NODB\n"; exit; }'
            . '$db->SetFetchMode(ADODB_FETCH_NUM);'
            . '$rs = $db->Execute("SELECT 1 UNION SELECT 2");'
            . 'foreach ($rs as $row) { echo "ROW:", $row[0], "\n"; }'
        );

        if (in_array('NODB', $lines, true)) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        $this->assertSame(['ROW:1', 'ROW:2'], array_values(preg_grep('/^ROW:/', $lines)));
        $this->assertSame([], array_values(preg_grep('/Return type of/', $lines)));
    }
}
