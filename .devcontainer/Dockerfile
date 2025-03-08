FROM mcr.microsoft.com/vscode/devcontainers/php:7.4

# remove yarn, it is not needed and creates an error
RUN rm -f /etc/apt/sources.list.d/yarn.list* && \
    apt-get update && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get -y install --no-install-recommends \
        mariadb-client \
        && \
    apt-get clean -y && \
    rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install \
    mysqli

WORKDIR /workspace

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1
