<?php

use PHPUnit\Framework\TestCase;

class DockerExportEnvTest extends TestCase
{
    private $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/vsp-env-' . getmypid();
        mkdir($this->dir . '/bin', 0777, true);
        // fake "cron" so the script does not start a daemon
        file_put_contents($this->dir . '/bin/cron', "#!/bin/sh\nexit 0\n");
        chmod($this->dir . '/bin/cron', 0755);
    }

    protected function tearDown(): void
    {
        exec('rm -rf ' . escapeshellarg($this->dir));
    }

    public function testExportedEnvironmentSurvivesSpecialCharacters(): void
    {
        $file = $this->dir . '/docker_env.sh';
        $script = dirname(__DIR__) . '/docker/export_env.sh';
        $password = 'pa$$"w`touch ' . $this->dir . '/pwned`\\x';
        $quote = 'He said "hi" & left';

        $cmd = 'env DOCKER_ENV_FILE=' . escapeshellarg($file)
            . ' DB_PASSWORD=' . escapeshellarg($password)
            . ' SERVER_QUOTE=' . escapeshellarg($quote)
            . ' PATH=' . escapeshellarg($this->dir . '/bin:' . getenv('PATH'))
            . ' sh ' . escapeshellarg($script) . ' 2>&1';
        exec($cmd, $out, $rc);
        $this->assertSame(0, $rc, implode("\n", $out));

        $read = 'bash -c ' . escapeshellarg('source ' . escapeshellarg($file) . '; printf "%s\n%s" "$DB_PASSWORD" "$SERVER_QUOTE"') . ' 2>&1';
        $lines = [];
        exec($read, $lines);

        $this->assertSame($password . "\n" . $quote, implode("\n", $lines));
        $this->assertFileDoesNotExist($this->dir . '/pwned');
        $this->assertSame('0600', substr(sprintf('%o', fileperms($file)), -4));
    }
}
