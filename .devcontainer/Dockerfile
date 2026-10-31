FROM mcr.microsoft.com/devcontainers/php:8.5

# remove yarn, it is not needed and creates an error
RUN rm -f /etc/apt/sources.list.d/yarn.list* && \
    # Add Docker and repository
    curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg && \
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list && \
    apt-get update && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get -y install --no-install-recommends \
        docker-buildx-plugin \
        docker-ce-cli \
        docker-compose-plugin \
        libjpeg-dev \
        libpng-dev \
        mariadb-client \
        && \
    apt-get clean -y && \
    rm -rf /var/lib/apt/lists/* && \
    # PHP extensions
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install \
    ftp gd mysqli

WORKDIR /workspace

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1
