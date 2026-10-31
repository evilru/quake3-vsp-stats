<?php

use PHPUnit\Framework\TestCase;

class VspWebLoginTest extends TestCase
{
    private const PASSWORD = 'QNKCDZO'; // md5() starts with "0e" followed by digits only

    private const MAGIC_HASH_COLLISION = '240610708'; // same for this string

    private $server;

    private $passwordFileBackup;

    protected function setUp(): void
    {
        $file = dirname(__DIR__) . '/password.inc.php';
        $this->passwordFileBackup = file_get_contents($file);
        file_put_contents($file, "<?php\n\$vsp['password']=\"" . self::PASSWORD . "\";\n");
    }

    protected function tearDown(): void
    {
        file_put_contents(dirname(__DIR__) . '/password.inc.php', $this->passwordFileBackup);
        if (is_resource($this->server)) {
            proc_terminate($this->server);
            proc_close($this->server);
        }
    }

    private function login(string $password): string
    {
        $sock = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        $port = (int) substr(strrchr(stream_socket_get_name($sock, false), ':'), 1);
        fclose($sock);
        $cmd = [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=-1', '-S', '127.0.0.1:' . $port, '-t', dirname(__DIR__)];
        $this->server = proc_open($cmd, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes);
        for ($i = 0; $i < 50 && !@fsockopen('127.0.0.1', $port); $i++) {
            usleep(100000);
        }
        $context = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query(['password' => $password, 'CMD_LINE_ARGS' => '']),
            'ignore_errors' => true,
        ]]);
        return (string) file_get_contents('http://127.0.0.1:' . $port . '/vsp.php?mode=web', false, $context);
    }

    public function testCorrectPasswordIsAccepted(): void
    {
        $this->assertStringNotContainsString('Invalid password', $this->login(self::PASSWORD));
    }

    public function testWrongPasswordIsRejected(): void
    {
        $this->assertStringContainsString('Invalid password', $this->login('wrong-password'));
    }

    public function testDifferentPasswordWithEqualMagicHashIsRejected(): void
    {
        $this->assertStringContainsString('Invalid password', $this->login(self::MAGIC_HASH_COLLISION));
    }
}
