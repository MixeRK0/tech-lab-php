FROM yiisoftware/yii2-php:7.2-apache

ARG GIT_SERVER
ARG SSH_PRIVATE_KEY

ENV ACCEPT_EULA=Y

# Microsoft SQL Server Prerequisites
RUN apt-get update \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        unixodbc-dev \
        msodbcsql17

RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

ARG GITLAB_HOST
ARG GITLAB_TOKEN

RUN composer config --global gitlab-token.${GITLAB_HOST} ${GITLAB_TOKEN}

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf
