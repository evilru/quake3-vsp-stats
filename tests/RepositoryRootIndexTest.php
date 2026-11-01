<?php

use PHPUnit\Framework\TestCase;

/** The repository root index.php is the landing page when the whole repository is the document root (Docker image). */
class RepositoryRootIndexTest extends TestCase
{
    private $server;

    protected function tearDown(): void
    {
        if (is_resource($this->server)) {
            proc_terminate($this->server);
            proc_close($this->server);
        }
    }

    private function request(string $requestLine): string
    {
        $sock = stream_socket_server('tcp://127.0.0.1:0');
        $port = (int) substr(strrchr(stream_socket_get_name($sock, false), ':'), 1);
        fclose($sock);
        $cmd = [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=-1', '-S', '127.0.0.1:' . $port, '-t', dirname(__DIR__)];
        $this->server = proc_open($cmd, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes);
        for ($i = 0; $i < 50 && !@fsockopen('127.0.0.1', $port); $i++) {
            usleep(100000);
        }
        $socket = fsockopen('127.0.0.1', $port);
        fwrite($socket, $requestLine . " HTTP/1.0\r\n\r\n");
        $body = (string) stream_get_contents($socket);
        fclose($socket);
        return $body;
    }

    public function testRedirectKeepsQueryString(): void
    {
        $body = $this->request('GET /index.php?config=cfg-default.php&sort=kills');

        $this->assertStringContainsString('url=pub/themes/bismarck/index.php?config=cfg-default.php&sort=kills">', $body);
        $this->assertStringContainsString('window.location="pub/themes/bismarck/index.php?config=cfg-default.php&sort=kills"', $body);
    }

    public function testRedirectDoesNotReflectRawQueryStringMarkup(): void
    {
        $body = $this->request("GET /index.php?a=\"></script><script>alert(1)</script>");

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }
}
