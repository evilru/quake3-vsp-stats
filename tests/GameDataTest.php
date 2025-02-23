<?php

use PHPUnit\Framework\TestCase;

class GameDataTest extends TestCase
{
    protected $gameData;
    protected $dbMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create DB mock before resetting state
        $this->dbMock = $this->createMock(\ADOConnection::class);
        $this->dbMock->expects($this->any())
            ->method('qstr')
            ->willReturnCallback(function($str) {
                return "'$str'";
            });

        // Set mock after state reset
        $GLOBALS['V9c1ebee8'] = $this->dbMock;

        $this->gameData = new F02ac4643();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->gameData = null;
        $GLOBALS['V9c1ebee8'] = null;
        resetGlobalState();
    }

    public function testPlayerInitialization()
    {
        $playerId = "player1";
        $playerName = "TestPlayer";
        $ip = "127.0.0.1";
        $tld = "de";

        $this->gameData->F6aae4907($playerId, $playerName, $ip, $tld);

        $players = $this->gameData->F26dd5333();
        $this->assertArrayHasKey($playerId, $players);
        $this->assertEquals($playerName, $players[$playerId]['profile']['name']);
        $this->assertEquals($ip, $players[$playerId]['profile']['ip']);
        $this->assertEquals($tld, $players[$playerId]['profile']['tld']);
    }
}
