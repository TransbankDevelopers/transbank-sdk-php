FROM mcr.microsoft.com/devcontainers/php:4-8.5-trixie

RUN apt-get update \
    && apt-get install -y --no-install-recommends ripgrep \
    && rm -rf /var/lib/apt/lists/*
