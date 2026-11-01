<?php

use PHPUnit\Framework\TestCase;

class WebIndexTest extends TestCase
{
    private $server;

    private $settingsBackup;

    private $port;

    protected function tearDown(): void
    {
        if ($this->settingsBackup !== null) {
            file_put_contents(dirname(__DIR__) . '/pub/themes/bismarck/settings.php', $this->settingsBackup);
        }
        if (is_resource($this->server)) {
            proc_terminate($this->server);
            proc_close($this->server);
        }
    }

    private function get(string $path, ?array $post = null): string
    {
        $sock = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        $port = (int) substr(strrchr(stream_socket_get_name($sock, false), ':'), 1);
        fclose($sock);
        $this->port = $port;
        $cmd = [PHP_BINARY, '-d', 'display_errors=1', '-d', 'error_reporting=-1', '-S', '127.0.0.1:' . $port, '-t', dirname(__DIR__) . '/pub'];
        $this->server = proc_open($cmd, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes);
        for ($i = 0; $i < 50; $i++) {
            if (@fsockopen('127.0.0.1', $port)) {
                break;
            }
            usleep(100000);
        }
        $context = $post === null ? null : stream_context_create(['http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($post),
        ]]);
        return (string) file_get_contents('http://127.0.0.1:' . $port . $path, false, $context);
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

    public function testHitboxPolygonDrawingHasNoNumPointsDeprecation(): void
    {
        $body = $this->get('/themes/bismarck/hitbox.php?H[head]=5&H[ALL]=9&H[torso]=2');

        $this->assertStringNotContainsString('imagefilledpolygon()', $body);
    }

    public function testPlayerPageForEmptyPlayerIdHasNoOffsetOrPropertyWarnings(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=&config=cfg-default.php');

        $this->assertStringContainsString('</HTML>', strtoupper($body));
        $this->assertDoesNotMatchRegularExpression(
            '/Trying to access array offset|Trying to get property|Attempt to read property/',
            $body
        );
    }

    public function testGamePageForUnknownGameRendersCompletely(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=1&config=cfg-default.php');

        $this->assertStringNotContainsString('Fatal error', $body);
        $this->assertStringContainsString('</HTML>', strtoupper($body));
    }

    public function testPlayerPageForUnknownPlayerShowsNoPhpMessages(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=nobody&config=cfg-default.php');

        $this->assertDoesNotMatchRegularExpression('/(?:Warning|Deprecated|Notice)(?:<\/b>)?:/', $body);
    }

    public function testGamePageForUnknownGameShowsNoPhpMessages(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=1&config=cfg-default.php');

        $this->assertDoesNotMatchRegularExpression('/(?:Warning|Deprecated|Notice)(?:<\/b>)?:/', $body);
    }

    public function testAwardPageForUnknownAwardShowsNoPhpMessages(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/awardstat.php?awardID=zz&config=cfg-default.php');

        $this->assertDoesNotMatchRegularExpression('/(?:Warning|Deprecated|Notice)(?:<\/b>)?:/', $body);
    }

    public function testUnknownSkinRedirectsWithoutPhpMessages(): void
    {
        $body = $this->get('/themes/bismarck/index.php?skin=nonexistent&config=cfg-default.php');

        $this->assertStringContainsString('window.location', $body);
        $this->assertDoesNotMatchRegularExpression('/(?:Warning|Deprecated|Notice)(?:<\/b>)?:/', $body);
    }

    public function testPlayerPageForEmptyPlayerIdWithCountryFlagsEnabledShowsNoPhpMessages(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');
        $file = dirname(__DIR__) . '/pub/themes/bismarck/settings.php';
        $this->settingsBackup = file_get_contents($file);
        file_put_contents($file, str_replace("['no_country_flags']=1", "['no_country_flags']=0", $this->settingsBackup));

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=&config=cfg-default.php');

        $this->assertStringContainsString('images/flags/', $body);
        $this->assertDoesNotMatchRegularExpression('/(?:Warning|Deprecated|Notice)(?:<\/b>)?:/', $body);
    }

    public function testPlayerListSortsByRequestedColumn(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&sort=kills&order=ASC');

        $this->assertLessThan(strpos($body, 'Doom'), strpos($body, 'Uriel'));
    }

    public function testPlayerListIgnoresSqlInjectedSortParameter(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&sort=' . rawurlencode('kills ASC,playerID') . '&order=DESC');

        $this->assertLessThan(strpos($body, 'Uriel'), strpos($body, 'Doom'));
    }

    public function testPlayerListKeepsHistoricalRankFlagInPagingLinks(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&hist_rank=1');

        $this->assertStringContainsString('start_from=0&amp;hist_rank=1">first', $body);
    }

    public function testPlayerListEscapesHistRankParameter(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&hist_rank=' . rawurlencode('"><script>alert(1)</script>'));

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testRootPageRedirectKeepsQueryString(): void
    {
        $body = $this->get('/?config=cfg-default.php&sort=kills');

        $this->assertStringContainsString('url=themes/bismarck/index.php?config=cfg-default.php&sort=kills">', $body);
        $this->assertStringContainsString('window.location="themes/bismarck/index.php?config=cfg-default.php&sort=kills"', $body);
    }

    public function testRootPageRedirectDoesNotReflectRawQueryStringMarkup(): void
    {
        $this->get('/');
        $socket = fsockopen('127.0.0.1', $this->port);
        fwrite($socket, "GET /?a=\"></script><script>alert(1)</script> HTTP/1.0\r\n\r\n");
        $body = (string) stream_get_contents($socket);
        fclose($socket);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testPlayerListLinksUseScriptPath(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php');

        $this->assertStringContainsString('action="/themes/bismarck/index.php?config=cfg-default.php&amp;sort=skill&amp;hist_rank=', $body);
    }

    public function testPlayerListDoesNotReflectPathInfoMarkup(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php/' . rawurlencode('"><script>alert(1)</script>') . '?config=cfg-default.php');

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testPlayerSearchShowsSearchTermInHeading(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php', ['search_by' => 'name', 'search_txt' => 'Doom']);

        $this->assertStringContainsString('( search results for name = Doom )', $body);
    }

    public function testPlayerSearchEscapesSearchTermInHeading(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php', ['search_by' => 'name', 'search_txt' => '<script>alert(1)</script>']);

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testUnknownSkinRedirectKeepsSortParameter(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&skin=nonexistent&sort=kills');

        $this->assertStringContainsString('index.php?config=cfg-default.php&amp;sort=kills"', $body);
    }

    public function testUnknownSkinRedirectDoesNotReflectSortMarkup(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&skin=nonexistent&sort=' . rawurlencode('"</script><script>alert(1)</script>'));

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testKnownSkinIsAppliedToTheStylesheet(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&skin=xp');

        $this->assertStringContainsString('skin : xp by ', $body);
        $this->assertStringContainsString('./skins/xp/xp.css', $body);
        $this->assertStringNotContainsString('window.location', $body);
    }

    public function testSkinWithPathTraversalFallsBackToTheDefaultSkin(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        // ./skins/fest/../fest/fest/../fest.css resolves to the existing ./skins/fest/fest.css
        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php&skin=' . rawurlencode('fest/../fest'));

        $this->assertStringNotContainsString('fest/../fest', $body);
        $this->assertStringContainsString('window.location', $body);
    }

    public function testPlayerPageListsGuidInLinkAndTitle(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=ExcessivePlayer&config=cfg-default.php');

        $this->assertStringContainsString(
            '<A HREF="javascript:searchByGUID(\'09A452A4807B9C5E9213048814AF5982\');" TITLE="09A452A4807B9C5E9213048814AF5982">09A452A4807B9C5E9213048814AF5982</A>',
            $body
        );
    }

    public function testPlayerPageDoesNotReflectMarkupFromTheGuidOfALog(): void
    {
        $log = tempnam(sys_get_temp_dir(), 'vsp');
        file_put_contents($log, str_replace(
            '09A452A4807B9C5E9213048814AF5982',
            '\');"><script>alert(1)</script>',
            file_get_contents(dirname(__DIR__) . '/xp.log')
        ));
        $this->importBaseq3Database('q3a-xp', $log);
        unlink($log);

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=ExcessivePlayer&config=cfg-default.php');

        $this->assertStringContainsString('searchByGUID(', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
        $this->assertStringNotContainsString("');\"><script>", $body);
    }

    public function testPlayerPageListsAliasesAsSearchLinks(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=Bitterman&config=cfg-default.php');

        $this->assertStringContainsString('<A href="javascript:searchByName(\'Bitterman\');"', $body);
    }

    public function testPlayerPageDoesNotReflectMarkupFromAPlayerNameInTheAliasLink(): void
    {
        $log = tempnam(sys_get_temp_dir(), 'vsp');
        file_put_contents($log, str_replace(
            'n\\Doom\\',
            'n\\x"><script>alert(1)</script>\\',
            file_get_contents(dirname(__DIR__) . '/xp.log')
        ));
        $this->importBaseq3Database('q3a-xp', $log);
        unlink($log);

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=' . rawurlencode('x"><script>alert(1)</script>') . '&config=cfg-default.php');

        $this->assertStringContainsString('searchByName(', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testGameListShowsTheMapInTheTitleOfTheGameLink(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');
        $file = dirname(__DIR__) . '/pub/themes/bismarck/settings.php';
        $this->settingsBackup = file_get_contents($file);
        file_put_contents($file, str_replace("['mini_gamestats_list']=0", "['mini_gamestats_list']=1", $this->settingsBackup));


        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php');

        $this->assertMatchesRegularExpression('/<A CLASS="tooltip" TITLE="[^"<>]*, arenagate" HREF="gamestat\.php\?gameID=\d+&amp;config=cfg-default\.php">/', $body);
    }

    public function testGameListDoesNotReflectMarkupFromTheMapOfALog(): void
    {
        $log = tempnam(sys_get_temp_dir(), 'vsp');
        file_put_contents($log, str_replace(
            'mapname\\arenagate\\',
            'mapname\\x"><script>alert(1)</script>\\',
            file_get_contents(dirname(__DIR__) . '/xp.log')
        ));
        $this->importBaseq3Database('q3a-xp', $log);
        unlink($log);
        $file = dirname(__DIR__) . '/pub/themes/bismarck/settings.php';
        $this->settingsBackup = file_get_contents($file);
        file_put_contents($file, str_replace("['mini_gamestats_list']=0", "['mini_gamestats_list']=1", $this->settingsBackup));


        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php');

        $this->assertStringContainsString('gamestat.php?gameID=', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    private function firstGameId(): string
    {
        return trim((string) shell_exec('mysql -N -e "SELECT MIN(gameID) FROM vsp.vsp_gamedata WHERE name=\'_v_map\'"'));
    }

    private function importXpLogWith(string $search, string $replace): void
    {
        $log = tempnam(sys_get_temp_dir(), 'vsp');
        file_put_contents($log, str_replace($search, $replace, file_get_contents(dirname(__DIR__) . '/xp.log')));
        $this->importBaseq3Database('q3a-xp', $log);
        unlink($log);
    }

    public function testGamePageShowsTheMap(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=' . $this->firstGameId() . '&config=cfg-default.php');

        $this->assertMatchesRegularExpression('/>Map<\/TD>\s*<TD [^>]*>arenagate<\/TD>/', $body);
    }

    public function testGamePageDoesNotReflectMarkupFromTheMapOfALog(): void
    {
        $this->importXpLogWith('mapname\\arenagate\\', 'mapname\\x<script>alert(1)</script>\\');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=' . $this->firstGameId() . '&config=cfg-default.php');

        $this->assertStringContainsString('>Map</TD>', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testGamePageShowsTheMod(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=' . $this->firstGameId() . '&config=cfg-default.php');

        $this->assertMatchesRegularExpression('/>Mod<\/TD><TD [^>]*>excessiveplus<\/TD>/', $body);
    }

    public function testGamePageDoesNotReflectMarkupFromTheModOfALog(): void
    {
        $this->importXpLogWith('gamename\\excessiveplus\\', 'gamename\\x<script>alert(1)</script>\\');

        $body = $this->get('/themes/bismarck/gamestat.php?gameID=' . $this->firstGameId() . '&config=cfg-default.php');

        $this->assertStringContainsString('>Mod</TD>', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testGameListPageShowsTheMapOfEachGame(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/gamestat.php?config=cfg-default.php');

        $this->assertMatchesRegularExpression('/<TD style="text-align: center;">\s*arenagate\s*<\/TD>/', $body);
    }

    public function testGameListPageDoesNotReflectMarkupFromTheMapOfALog(): void
    {
        $this->importXpLogWith('mapname\\arenagate\\', 'mapname\\x<script>alert(1)</script>\\');

        $body = $this->get('/themes/bismarck/gamestat.php?config=cfg-default.php');

        $this->assertStringContainsString('>Details', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    public function testPlayerPageShowsTheMapInTheTitleOfTheGameLink(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=Bitterman&config=cfg-default.php');

        $this->assertMatchesRegularExpression('/<A CLASS="tooltip" TITLE="[^"<>]*, arenagate" HREF="gamestat\.php\?gameID=\d+&amp;config=cfg-default\.php">/', $body);
    }

    public function testPlayerPageDoesNotReflectMarkupFromTheMapOfALog(): void
    {
        $this->importXpLogWith('mapname\\arenagate\\', 'mapname\\x"><script>alert(1)</script>\\');

        $body = $this->get('/themes/bismarck/playerstat.php?playerID=Bitterman&config=cfg-default.php');

        $this->assertStringContainsString('gamestat.php?gameID=', $body);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
    }

    private function enableCountryFlags(): void
    {
        $file = dirname(__DIR__) . '/pub/themes/bismarck/settings.php';
        $this->settingsBackup = file_get_contents($file);
        file_put_contents($file, str_replace("['no_country_flags']=1", "['no_country_flags']=0", $this->settingsBackup));
    }

    public function testPlayerListShowsTheCountryFlag(): void
    {
        $this->importBaseq3Database('q3a-xp', 'xp.log');
        $this->enableCountryFlags();

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php');

        $this->assertStringContainsString('alt="XX" src="../../images/flags/xx.gif"', $body);
    }

    public function testPlayerListDoesNotReflectMarkupFromTheCountryOfALog(): void
    {
        $this->importXpLogWith('\\tld\\\\country', '\\tld\\<>\\country');
        $this->enableCountryFlags();

        $body = $this->get('/themes/bismarck/index.php?config=cfg-default.php');

        $this->assertStringContainsString('images/flags/', $body);
        $this->assertStringNotContainsString('alt="<>"', $body);
    }

    private function importBaseq3Database(string $type = 'q3a', string $log = 'baseq3.log'): void
    {
        $root = dirname(__DIR__);
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        exec('cd ' . escapeshellarg($root) . ' && ' . escapeshellarg(PHP_BINARY)
            . ' -d display_errors=1 -d error_reporting=-1 vsp.php -c pub/configs/cfg-default.php -l ' . $type . ' -p savestate 1 ' . $log . ' >/dev/null 2>&1 </dev/null');
    }
}
