<?php

use PHPUnit\Framework\TestCase;

class VspTest extends TestCase
{
    private $validLogTypes = [
        'client',
        'q3a',
        'q3a-battle',
        'q3a-cpma',
        'q3a-freeze',
        'q3a-lrctf',
        'q3a-osp',
        'q3a-ra3',
        'q3a-threewave',
        'q3a-ut',
        'q3a-xp'
    ];

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function testVersionConstantExists()
    {
        $this->assertTrue(defined('cVERSION'));
        $this->assertEquals('0.45-xp-1.1.2', cVERSION);
    }

    public function testDatabaseConnection()
    {
        $db = new PDO(
            "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        $this->assertNotNull($db);
    }

    /**
     * @dataProvider validLogTypesProvider
     */
    public function testValidLogTypes($logType)
    {
        $this->assertContains($logType, $this->validLogTypes);
    }

    public function validLogTypesProvider()
    {
        return array_map(function($type) {
            return [$type];
        }, $this->validLogTypes);
    }

    public function testConstantsAreDefined()
    {
        $this->assertTrue(defined('cTITLE'));
        $this->assertTrue(defined('cUSAGE'));
    }
}
