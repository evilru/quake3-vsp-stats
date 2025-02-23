<?php

use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        resetGlobalState();
    }

    public function testCommandLineOptionParsing()
    {
        $_SERVER['argc'] = 4;
        $_SERVER['argv'] = [
            'vsp.php',
            '-l',
            'q3a',
            'games.log'
        ];

        F92261ca6(); // Parse command line

        global $V0f14082c;
        $this->assertEquals('q3a', $V0f14082c['log-gamecode']);
        $this->assertEquals('games.log', $V0f14082c['logfile']);
    }

    public function testGameTypeOptionParsing()
    {
        $_SERVER['argc'] = 4;
        $_SERVER['argv'] = [
            'vsp.php',
            '-l',
            'q3a-osp',
            'games.log'
        ];

        F92261ca6();

        global $V0f14082c;
        $this->assertEquals('q3a', $V0f14082c['log-gamecode']);
        $this->assertEquals('osp', $V0f14082c['log-gametype']);
    }
}
