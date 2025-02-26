FROM mcr.microsoft.com/vscode/devcontainers/php:7.4

# System packages and cleanup in one layer
RUN rm -f /etc/apt/sources.list.d/yarn.list* && \
    apt-get update && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get -y install --no-install-recommends \
        mariadb-client \
        git \
        unzip \
        curl \
        sqlite3 \
        libzip-dev \
        libicu-dev \
        libgd-dev \
        libxml2-dev \
        libonig-dev && \
    apt-get clean -y && \
    rm -rf /var/lib/apt/lists/*

# PHP extensions in one layer
RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    zip \
    intl \
    gd \
    xml \
    mbstring


# PHP configurations in one layer
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/docker-php-memory-limit.ini && \
echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/docker-php-max-execution-time.ini && \
echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/docker-php-upload-max-filesize.ini && \
echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/docker-php-post-max-size.ini

COPY php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Node.js setup with fnm
RUN bash -c 'curl -fsSL https://fnm.vercel.app/install | bash && \
    echo 'eval "$(fnm env --use-on-cd)"' >> /root/.bashrc && \
    . /root/.bashrc && \
    fnm install 22 && \
    fnm default 22'

# Final configuration
RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b /usr/local/bin && \
    git config --system core.editor "code --wait" && \
    git config --system pull.rebase true

# Environment setup
ENV PATH="/home/vscode/.composer/vendor/bin:/root/.composer/vendor/bin:${PATH}"

WORKDIR /workspace

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1
