<?php

use PHPUnit\Framework\TestCase;

class WebIndexTest extends TestCase
{
    private $server;

    protected function tearDown(): void
    {
        if (is_resource($this->server)) {
            proc_terminate($this->server);
            proc_close($this->server);
        }
    }

    private function get(string $path): string
    {
        $sock = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        $port = (int) substr(strrchr(stream_socket_get_name($sock, false), ':'), 1);
        fclose($sock);
        $cmd = [PHP_BINARY, '-S', '127.0.0.1:' . $port, '-t', dirname(__DIR__) . '/pub'];
        $this->server = proc_open($cmd, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes);
        for ($i = 0; $i < 50; $i++) {
            if (@fsockopen('127.0.0.1', $port)) {
                break;
            }
            usleep(100000);
        }
        return (string) file_get_contents('http://127.0.0.1:' . $port . $path);
    }

    public function testRootPageRedirectsToThemeWithoutWarnings(): void
    {
        $body = $this->get('/');

        $this->assertStringContainsString('url=themes/bismarck/index.php?', $body);
        $this->assertStringNotContainsString('QUERY_STRING', $body);
    }

    public function testThemeIndexRendersWithoutMissingQueryStringWarning(): void
    {
        $this->importBaseq3Database();

        $body = $this->get('/themes/bismarck/index.php');

        $this->assertStringContainsString('Skins:', $body);
        $this->assertStringNotContainsString('QUERY_STRING', $body);
    }

    public function testThemeIndexHasNoReferenceAssignmentNotice(): void
    {
        $this->importBaseq3Database();

        $body = $this->get('/themes/bismarck/index.php');

        $this->assertStringContainsString('Skins:', $body);
        $this->assertStringNotContainsString('assigned by reference', $body);
    }

    private function importBaseq3Database(): void
    {
        $root = dirname(__DIR__);
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        exec('cd ' . escapeshellarg($root) . ' && ' . escapeshellarg(PHP_BINARY)
            . ' vsp.php -c pub/configs/cfg-default.php -l q3a -p savestate 1 baseq3.log >/dev/null 2>&1 </dev/null');
    }
}
