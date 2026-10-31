#!/usr/bin/env python3
"""Save the HTML of fixed valid requests so a change can be diffed against the previous state.
Usage: scripts/web-snapshot.py <out-dir>   (DB must hold an import, e.g. scripts/import.sh q3a-xp xp.log)
Compare: diff -r <before> <after>   (must print nothing for security fixes)"""
import os, subprocess, sys, time, urllib.request, urllib.parse

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
T = "/themes/bismarck/"
PAGES = [
    "/", T + "index.php", T + "index.php?config=cfg-default.php&sort=kills&order=ASC",
    T + "index.php?sort=efficiency", T + "index.php?sort=kd&order=ASC&start_from=2",
    T + "index.php?sort=((kills*100)/(1%2Bkills%2Bdeaths))&order=DESC&hist_rank=1",
    T + "index.php?sort=playerName&order=ASC", T + "index.php?skin=camo",
    T + "playerstat.php?playerID=Bitterman&config=cfg-default.php",
    T + "awardstat.php", T + "gamestat.php?gameID=1",
]

def main():
    out = sys.argv[1]; os.makedirs(out, exist_ok=True)
    port = 8765
    srv = subprocess.Popen(["php", "-d", "display_errors=1", "-d", "error_reporting=-1", "-S", "127.0.0.1:%d" % port, "-t", ROOT + "/pub"],
                           stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
    try:
        time.sleep(1)
        for i, p in enumerate(PAGES):
            try:
                body = urllib.request.urlopen("http://127.0.0.1:%d%s" % (port, p), timeout=60).read()
            except Exception as e:
                body = ("ERROR %s" % e).encode()
            open("%s/%02d.html" % (out, i), "wb").write(body)
    finally:
        srv.terminate()

main()
