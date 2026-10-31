<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vsp-q3a.php'; // Include the VSPParserQ3A class

class ConvertColorCodesTest extends TestCase
{
    protected static $gameDataProcessor;
    protected static $playerSkillProcessor;
    protected $q3aParser;
    protected $xpParser;
    protected $xpParser103;

    public static function setUpBeforeClass(): void
    {
        self::$gameDataProcessor = new GameDataProcessor();
        self::$playerSkillProcessor = new PlayerSkillProcessor();
    }

    protected function setUp(): void
    {
        $q3aConfig = ["gametype" => "q3a"];
        $xpConfig = ["gametype" => "xp"];
        $xpConfig103 = ["gametype" => "xp", "xp_version" => 103];

        $this->q3aParser = new VSPParserQ3A($q3aConfig, self::$gameDataProcessor, self::$playerSkillProcessor);
        $this->xpParser = new VSPParserQ3A($xpConfig, self::$gameDataProcessor, self::$playerSkillProcessor);
        $this->xpParser103 = new VSPParserQ3A($xpConfig103, self::$gameDataProcessor, self::$playerSkillProcessor);
    }

    private function invokeMethod($parser, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($parser));
        $method = $reflection->getMethod($methodName);
        if (PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }
        return $method->invokeArgs($parser, $parameters);
    }

    public function testQ3aConvertAllColorCodes()
    {
        $input = "^1Red ^2Green ^3Blue ^4Yellow ^5Cyan ^6Magenta ^7White ^0Gray";
        $expected = "`#FFFFFF`#FF0000Red `#00FF00Green `#FFFF00Blue `#4444FFYellow `#00FFFFCyan `#FF00FFMagenta `#FFFFFFWhite `#777777Gray";
        $actual = $this->invokeMethod($this->q3aParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testQ3aConvertEmptyName()
    {
        $input = "";
        $expected = " ";
        $actual = $this->invokeMethod($this->q3aParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testQ3aConvertColorCodesWithSpecialChar()
    {
        $input = "^fSpecialChar";
        $expected = "`#FFFFFFSpecialChar";
        $actual = $this->invokeMethod($this->q3aParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testQ3aConvertColorCodesWithHexColor()
    {
        $input = "^xFF0000Red";
        $expected = "`#FFFFFF`#FF0000Red";
        $actual = $this->invokeMethod($this->q3aParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testQ3aConvertAllListedColors()
    {
        $input = "^0Gray ^1Red ^2Green ^3Yellow ^4Blue ^5Cyan ^6Magenta ^7White";
        $expected = "`#FFFFFF`#777777Gray `#FF0000Red `#00FF00Green `#FFFF00Yellow `#4444FFBlue `#00FFFFCyan `#FF00FFMagenta `#FFFFFFWhite";
        $actual = $this->invokeMethod($this->q3aParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertAllColorCodes()
    {
        $input = "^1Red ^2Green ^3Blue ^4Yellow ^5Cyan ^6Magenta ^7White ^0Gray";
        $expected = "`#e90000Red `#00dd24Green `#f5d800Blue `#2e61c8Yellow `#16b4a5Cyan `#f408f1Magenta `#efefefWhite `#555555Gray";
        $actual = $this->invokeMethod($this->xpParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertColorCodesWithSpecialChar()
    {
        $input = "#23SpecialChar";
        $expected = "`#efefef" . chr(hexdec('23')) . "SpecialChar";
        $actual = $this->invokeMethod($this->xpParser, 'convertXPColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertSpecialChars103()
    {
        $input = "+A#SpecialChar";
        $expected = "`#efefef@SpecialChar";
        $actual = $this->invokeMethod($this->xpParser103, 'convertXPColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertEmptyName()
    {
        $input = "";
        $expected = "`#efefef";
        $actual = $this->invokeMethod($this->xpParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertColorCodesWithHexColor()
    {
        $input = "^xFF0000Red";
        $expected = "`#FF0000Red";
        $actual = $this->invokeMethod($this->xpParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }

    public function testXpConvertAllListedColors()
    {
        $input = "^0Gray ^1Red ^2Green ^3Yellow ^4Blue ^5Cyan ^6Magenta ^7White";
        $expected = "`#555555Gray `#e90000Red `#00dd24Green `#f5d800Yellow `#2e61c8Blue `#16b4a5Cyan `#f408f1Magenta `#efefefWhite";
        $actual = $this->invokeMethod($this->xpParser, 'convertColorCodes', [$input]);
        $this->assertEquals($expected, $actual);
    }
}

// Mock classes for GameDataProcessor and PlayerSkillProcessor
class GameDataProcessor {}
class PlayerSkillProcessor {}
