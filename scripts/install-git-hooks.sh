#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
git config core.hooksPath .githooks
echo "Set git hooksPath to .githooks (post-merge runs composer install after pull)."
