#!/usr/bin/env python3
"""Run the CLI imports and a web crawl for config/settings variants and print deduplicated PHP messages.
Usage: scripts/variants.py [variant-name ...]   (PHP version: current `php`; DB must be running)
Temporary files pub/configs/cfg-variant.php and pub/themes/bismarck/settings.php.bak are restored afterwards."""
import os, re, shutil, subprocess, sys, time, urllib.request, html, collections

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CFG = ROOT + "/pub/configs/cfg-default.php"
VAR = ROOT + "/pub/configs/cfg-variant.php"
SET = ROOT + "/pub/themes/bismarck/settings.php"
LOGS = [("q3a", "baseq3.log"), ("q3a-xp", "xp.log"), ("q3a-osp", "osp.log")]

def cfg(*pairs):
    return ("cfg", pairs)
def setting(*pairs):
    return ("set", pairs)

# (name, [(kind, [(regex, replacement)])]) -- replacements apply to the default file content
VARIANTS = {
    "default": [],
    "originalid-off": [cfg((r"(\['use_original_playerID'\] = )1", r"\g<1>0"))],
    "mostused-off": [cfg((r"(\['use_most_used_playerName'\] = )1", r"\g<1>0"), (r"(\['use_most_used_playerIP'\] = )1", r"\g<1>0"))],
    "uniqueid-off": [cfg((r"(\['check_unique_gameID'\] = )1", r"\g<1>0"))],
    "games-limit-small": [cfg((r"(\['games_limit'\] = )1000", r"\g<1>1"))],
    "games-limit-unlimited": [cfg((r"(\['games_limit'\] = )1000", r"\g<1>-1"))],
    "ip2country-full": [cfg((r"(\['countries_only'\] = )1", r"\g<1>0"))],
    "inactivity-off": [cfg((r"(\['days_inactivity'\]=)30", r"\g<1>0"))],
    "record-limit-5": [cfg((r"(\['record_limit'\]=)50", r"\g<1>5"))],
    "ui-minimal": [setting((r"(\['skin_selector'\]=)1", r"\g<1>0"), (r"(\['color_names'\]=)1", r"\g<1>0"),
                           (r"(\['server_info'\]=)1", r"\g<1>0"), (r"(\['javascript_tooltips'\]=)1", r"\g<1>0"),
                           (r"(\['gamestats_killchart'\]=)27", r"\g<1>0"), (r"(\['gamestats'\]=)1", r"\g<1>0"))],
    "ui-extras": [setting((r"(\['mini_gamestats_list'\]=)0", r"\g<1>1"), (r"(\['no_country_flags'\]=)1", r"\g<1>0"),
                          (r"(\['default_sort'\]=)'skill'", r"\g<1>'kills'"),
                          (r"(\['gamestats_killchart_per_weapon'\]=)1", r"\g<1>0"))],
}
for skin in sorted(os.listdir(ROOT + "/pub/themes/bismarck/skins")):
    if skin == "fest": continue  # default skin
    VARIANTS["skin-" + skin] = [setting((r"(\['default_skin'\]=)'fest'", r"\g<1>'%s'" % skin))]

def sh(cmd, **kw):
    return subprocess.run(cmd, shell=True, cwd=ROOT, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True, **kw).stdout

def messages(text):
    c = collections.Counter()
    for m in re.finditer(r"(Warning|Notice|Deprecated|Fatal error)(?:</b>)?:\s+([^\n<]{0,160})", text):
        c[m.group(1) + ": " + re.sub(r"/home/\S+/", "", m.group(2))] += 1
    return c

def main():
    names = sys.argv[1:] or list(VARIANTS)
    shutil.copy(SET, SET + ".bak")
    problems = collections.OrderedDict()
    try:
        for name in names:
            cfg_txt, set_txt = open(CFG).read(), open(SET + ".bak").read()
            for kind, pairs in VARIANTS[name]:
                for rx, rep in pairs:
                    if kind == "cfg":
                        new = re.sub(rx, rep, cfg_txt, count=1); assert new != cfg_txt, (name, rx); cfg_txt = new
                    else:
                        new = re.sub(rx, rep, set_txt, count=1); assert new != set_txt, (name, rx); set_txt = new
            open(VAR, "w").write(cfg_txt); open(SET, "w").write(set_txt)
            found = collections.Counter()
            for t, log in LOGS:
                sh('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp"')
                out = sh("php -d display_errors=1 -d error_reporting=-1 vsp.php -c pub/configs/cfg-variant.php -l %s -p savestate 1 %s </dev/null" % (t, log))
                found.update(messages(out))
            # web crawl on the last (osp) import and the xp import
            sh('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp"')
            sh("php vsp.php -c pub/configs/cfg-variant.php -l q3a-xp -p savestate 1 xp.log </dev/null")
            srv = subprocess.Popen(["php", "-d", "display_errors=1", "-d", "error_reporting=-1", "-S", "127.0.0.1:8099", "-t", ROOT + "/pub"],
                                   stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            time.sleep(1)
            try:
                base = "http://127.0.0.1:8099/themes/bismarck/"
                seen, queue = set(), ["index.php?config=cfg-variant.php", "index.php?hist_rank=1&config=cfg-variant.php"]
                while queue and len(seen) < 120:
                    u = queue.pop(0)
                    if u in seen: continue
                    seen.add(u)
                    try: b = urllib.request.urlopen(base + u, timeout=60).read().decode("utf8", "replace")
                    except Exception as e: found["HTTP error: %s %s" % (u[:40], e)] += 1; continue
                    found.update({m + "  [" + u[:70] + "]": n for m, n in messages(b).items()})
                    for h in re.findall(r'href="([^"#]+)"', b, re.I):
                        h = html.unescape(h)
                        if re.match(r"^(index|playerstat|gamestat|awardstat)\.php", h) and h not in seen: queue.append(h)
            finally:
                srv.terminate(); srv.wait()
            problems[name] = found
            print("%-24s %s" % (name, "OK" if not found else "%d message kinds" % len(found)), flush=True)
            for k, v in found.most_common(6): print("    %4d %s" % (v, k))
    finally:
        shutil.move(SET + ".bak", SET)
        if os.path.exists(VAR): os.remove(VAR)
    return 1 if any(problems.values()) else 0

sys.exit(main())
