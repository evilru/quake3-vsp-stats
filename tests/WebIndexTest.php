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

    public function testPlayerPageRendersWithoutUndefinedIpPart(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=Bitterman&config=default');

        $this->assertStringContainsString('Bitterman', $body);
        $this->assertDoesNotMatchRegularExpression('/Undefined (?:array key|offset)/', $body);
    }

    public function testGamePageRendersWithoutArrayOffsetWarning(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');
        $gameId = trim((string) shell_exec('mysql -N -e "SELECT gameID FROM vsp.vsp_gameprofile ORDER BY gameID LIMIT 1"'));

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=' . $gameId . '&config=default');

        $this->assertNotSame('', $gameId);
        $this->assertDoesNotMatchRegularExpression('/array offset on (?:value of type )?\w+/', $body);
    }

    public function testPlayerPageForPlayerWithoutWeaponDataRendersWithoutKsortError(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=ExcessivePlayer&config=cfg-default.php');

        $this->assertStringContainsString('ExcessivePlayer', $body);
        $this->assertStringNotContainsString('ksort()', $body);
    }

    public function testPlayerPageForUnknownPlayerRendersCompletely(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=nobody&config=cfg-default.php');

        $this->assertStringNotContainsString('Fatal error', $body);
        $this->assertStringContainsString('</HTML>', strtoupper($body));
    }

    public function testHitboxWithEmptyFilenameDoesNotAbortWithFatalError(): void
    {
        $body = $this->get('/themes/bismarck/hitbox.php?H[head]=5&H[ALL]=9&H[torso]=2');

        $this->assertStringNotContainsString('Fatal error', $body);
        $this->assertStringNotContainsString('ValueError', $body);
    }

    private function importBaseq3Database(string $type = 'q3a', string $log = 'baseq3.log'): void
    {
        $root = dirname(__DIR__);
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        exec('cd ' . escapeshellarg($root) . ' && ' . escapeshellarg(PHP_BINARY)
            . ' vsp.php -c pub/configs/cfg-default.php -l ' . $type . ' -p savestate 1 ' . $log . ' >/dev/null 2>&1 </dev/null');
    }
}
