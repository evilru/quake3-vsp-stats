<?php

use PHPUnit\Framework\TestCase;

class ComposerPlatformTest extends TestCase
{
    public function testCurrentPhpSatisfiesComposerPlatformRequirements(): void
    {
        exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && composer check-platform-reqs 2>&1', $lines, $rc);
        if ($rc === 127) {
            $this->markTestSkipped('composer not available');
        }
        $this->assertSame([], array_values(preg_grep('/^php\s.*failed/', $lines)), implode("\n", $lines));
    }
}
