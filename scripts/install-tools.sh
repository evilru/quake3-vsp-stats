#!/usr/bin/env bash
# Dev tools shared by the devcontainer (postCreateCommand) and scripts/setup-cloud.sh. npm + pip only, no apt repos.
# Needs node/npm. Run as root or as a user with sudo.
set -euo pipefail
SUDO=""; [ "$(id -u)" -eq 0 ] || SUDO="sudo"
command -v npm >/dev/null || { echo "npm missing" >&2; exit 1; }
command -v pip3 >/dev/null || { $SUDO apt-get update -qq && $SUDO apt-get install -y -qq python3-pip; }

npm install -g lefthook
# pip wheels bundle the binaries (editorconfig-checker's npm package downloads from GitHub, which fails behind proxies)
$SUDO env PIP_BREAK_SYSTEM_PACKAGES=1 pip3 install -q editorconfig-checker shellcheck-py hadolint-py actionlint-py
