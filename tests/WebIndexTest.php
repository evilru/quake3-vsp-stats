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
}
