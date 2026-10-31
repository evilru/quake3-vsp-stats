#!/bin/bash
# Run the import now and then every IMPORT_INTERVAL seconds (default: 900 = 15 minutes).
# After a failed import (e.g. the database is not ready yet) retry after IMPORT_RETRY seconds (default: 30).
interval="${IMPORT_INTERVAL:-900}"
retry="${IMPORT_RETRY:-30}"

while true; do
    if /vsp/docker/import.sh; then
        sleep "$interval"
    else
        echo "import failed (exit $?), retrying in ${retry}s" >&2
        sleep "$retry"
    fi
done
