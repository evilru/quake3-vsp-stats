<?php

use PHPUnit\Framework\TestCase;

class FtpDownloadCliTest extends TestCase
{
    private const CONFIG = 'cfg-ftp-test.php';

    private $server;

    private string $workDir;

    protected function setUp(): void
    {
        $this->workDir = sys_get_temp_dir() . '/vsp-ftp-' . bin2hex(random_bytes(4));
        mkdir($this->workDir . '/logs', 0777, true);
        $config = file_get_contents(dirname(__DIR__) . '/pub/configs/cfg-default.php');
        $config = str_replace("\$cfg['ftp']['pasv'] = 0;", "\$cfg['ftp']['pasv'] = 1;", $config);
        $config = str_replace('"./ftplogs/"', '"' . $this->workDir . '/logs/"', $config);
        file_put_contents(dirname(__DIR__) . '/pub/configs/' . self::CONFIG, $config);
    }

    protected function tearDown(): void
    {
        @unlink(dirname(__DIR__) . '/pub/configs/' . self::CONFIG);
        if (is_resource($this->server)) {
            proc_terminate($this->server);
            proc_close($this->server);
        }
        exec('rm -rf ' . escapeshellarg($this->workDir));
    }

    /** Downloads the directory ftp://host/remote/ from a fake server that lists one file. */
    private function downloadListedFile(string $listedName): void
    {
        if (!extension_loaded('ftp')) {
            $this->markTestSkipped('ftp extension not available');
        }
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        $sock = stream_socket_server('tcp://127.0.0.1:0');
        $port = (int) substr(strrchr(stream_socket_get_name($sock, false), ':'), 1);
        fclose($sock);
        $this->server = proc_open(
            [PHP_BINARY, __DIR__ . '/fixtures/fake-ftp-server.php', (string) $port, $listedName],
            [1 => ['pipe', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes
        );
        fgets($pipes[1]); // "ready": the server listens (a probe connection would use up its only accept)
        exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && ' . escapeshellarg(PHP_BINARY)
            . ' -d display_errors=1 -d error_reporting=-1 vsp.php -c ' . self::CONFIG
            . ' -l q3a -p savestate 1 ftp://127.0.0.1:' . $port . '/remote/ >/dev/null 2>&1 </dev/null');
    }

    public function testListedFileIsDownloadedIntoTheLogDirectory(): void
    {
        $this->downloadListedFile('games.log');

        $this->assertSame("log line\n", file_get_contents($this->workDir . '/logs/remote/games.log'));
    }

    public function testListedFileNameWithPathSegmentsDoesNotWriteOutsideTheLogDirectory(): void
    {
        $this->downloadListedFile('../../escaped.log');

        $this->assertFileDoesNotExist($this->workDir . '/escaped.log');
    }
}
