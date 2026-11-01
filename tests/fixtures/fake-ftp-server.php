<?php
// Minimal passive-mode FTP server for tests. Usage: php fake-ftp-server.php <port> <file name to list>
// Prints "ready" on stdout once it listens. Every RETR sends the content "log line\n" for the one listed regular file.
$port = (int) $argv[1];
$name = $argv[2];
$content = "log line\n";
$server = stream_socket_server('tcp://127.0.0.1:' . $port);
echo "ready\n";
$control = stream_socket_accept($server, 30);
$reply = function (string $line) use ($control) {
    fwrite($control, $line . "\r\n");
};
$data = null;
$reply('220 fake');
while (($line = fgets($control)) !== false) {
    $cmd = strtoupper(strtok(trim($line), ' '));
    switch ($cmd) {
        case 'USER':
            $reply('331 password');
            break;
        case 'PASS':
            $reply('230 ok');
            break;
        case 'SIZE':
            $reply('213 ' . strlen($content));
            break;
        case 'PASV':
            $dataServer = stream_socket_server('tcp://127.0.0.1:0');
            $dataPort = (int) substr(strrchr(stream_socket_get_name($dataServer, false), ':'), 1);
            $reply('227 Entering Passive Mode (127,0,0,1,' . intdiv($dataPort, 256) . ',' . $dataPort % 256 . ')');
            break;
        case 'LIST':
        case 'RETR':
            $reply('150 data');
            $data = stream_socket_accept($dataServer, 10);
            fwrite($data, $cmd === 'LIST'
                ? '-rw-r--r-- 1 ftp ftp ' . strlen($content) . ' Jan 01 2020 ' . $name . "\r\n"
                : $content);
            fclose($data);
            $reply('226 done');
            break;
        case 'QUIT':
            $reply('221 bye');
            break 2;
        default:
            $reply('200 ok');
    }
}
