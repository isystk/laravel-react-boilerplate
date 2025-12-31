FROM python:3.6

ARG awscli_version="1.19.80"

# PATHの設定
RUN mkdir -p /root/.local/bin
ENV PATH $PATH:/root/.local/bin

# 依存パッケージのインストール (curl, jq はここへまとめる)
RUN apt-get update && apt-get install -y \
    curl \
    jq \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# session-manager-plugin のインストール
RUN curl "https://s3.amazonaws.com/session-manager-downloads/plugin/latest/ubuntu_64bit/session-manager-plugin.deb" -o "session-manager-plugin.deb" \
    && dpkg -i session-manager-plugin.deb \
    && rm session-manager-plugin.deb

# aws-cli, sam, eb のインストール
RUN pip install --user --upgrade awscli==${awscli_version}
RUN pip install --user --upgrade aws-sam-cli
RUN pip install --user --upgrade awsebcli

WORKDIR /root
