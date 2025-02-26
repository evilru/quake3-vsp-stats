<?php
/* vsp stats processor, copyright 2004-2005, myrddin8 AT gmail DOT com (a924cb279be8cb6089387d402288c9f2) */

function parseFileListing(array $fileLines): array
{
    foreach ($fileLines as $line) {
        if (
            preg_match(
                "/([-dl][rwxst-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)/",
                $line,
                $matches
            )
        ) {
            // Determine file type (directory, link, etc.) by finding the position of the first char in "-dl"
            $fileType = (int) strpos("-dl", $matches[1][0]);
            $fileData["line"] = $matches[0];
            $fileData["type"] = $fileType;
            $fileData["rights"] = $matches[1];
            $fileData["number"] = $matches[2];
            $fileData["user"] = $matches[3];
            $fileData["group"] = $matches[4];
            $fileData["size"] = $matches[5];
            $fileData["date"] = date("m-d", strtotime($matches[6]));
            $fileData["time"] = $matches[7];
            $fileData["name"] = $matches[9];
            $result[] = $fileData;
        }
    }
    return $result;
}

function parseCommandLineArgs(string $inputStr): array
{
    while (
        preg_match('/^\s*"(.+)"/U', $inputStr, $match) ||
        preg_match("/^\s*([^\s]+)\s*/", $inputStr, $match)
    ) {
        $inputStr = str_replace($match[0], "", $inputStr);
        $args["argv"][] = $match[1];
    }
    $args["argc"] = count($args["argv"]);
    return $args;
}

function flushOutputBuffers(): void
{
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush();
}

function ensureTrailingSlash(string $path): string
{
    return rtrim($path, "\\/") . "/";
}

function copyDirectoryRecursive(string $sourceDir, string $destDir): void
{
    $sourceDir = rtrim($sourceDir, "/");
    $destDir = rtrim($destDir, "/");
    @mkdir($destDir, 0777);
    $items = getDirectoryListing($sourceDir);
    foreach ($items as $item) {
        if ($item) {
            $sourcePath = $sourceDir . "/" . $item;
            if (strcmp($sourcePath, $destDir) != 0) {
                $destPath = $destDir . "/" . $item;
                if (is_dir($sourcePath)) {
                    copyDirectoryRecursive($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
    }
}

function getDirectoryListing(string $dir): array
{
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (!isset($listingStr)) {
                    $listingStr = "$entry";
                } else {
                    $listingStr = "$entry\n$listingStr";
                }
            }
        }
        closedir($handle);
    }
    @$listing = explode("\n", $listingStr);
    return $listing;
}

function readStdinLine(int $maxLength = 255): string
{
    $stdinHandle = fopen("php://stdin", "r");
    $line = fgets($stdinHandle, $maxLength);
    $line = rtrim($line);
    fclose($stdinHandle);
    return $line;
}

function ensureDirectoryExists(string $dirPath): bool
{
    $dirPath = str_replace("\\", "/", $dirPath);
    if (!file_exists($dirPath)) {
        $currentPath = "";
        foreach (explode("/", $dirPath) as $part) {
            $currentPath .= $part . "/";
            if (!file_exists($currentPath)) {
                $created = mkdir($currentPath, 0775);
            }
        }
        return $created;
    }
    return true;
}

function sanitizeFilename(string $filename): string
{
    $filename = str_replace(['../', '..\\'], '', $filename);
    return str_replace(
        ["\\", "<", ">", "/", "=", ":", "*", "?", '"', " ", "|"],
        "_",
        $filename
    );
}

function secureString($value)
{
    global $db;
    if (!$db) {
        return addslashes($value);
    }
    return $db->qstr($value);
}

function getElapsedTime(array &$startTime): float
{
    $currentTime = gettimeofday();
    $elapsed =
        (float) ($currentTime["sec"] - $startTime["sec"]) +
        (float) ($currentTime["usec"] - $startTime["usec"]) / 1000000;
    return $elapsed;
}
?>
