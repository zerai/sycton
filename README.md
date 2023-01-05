# sycton

[![Iam service CD/CI](https://github.com/zerai/sycton/actions/workflows/iam-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/iam-service.yaml)
[![Blue service CD/CI](https://github.com/zerai/sycton/actions/workflows/blue-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/blue-service.yaml)
[![Red service CD/CI](https://github.com/zerai/sycton/actions/workflows/red-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/red-service.yaml)


## iam service 

Endpoint: [iam.localtest.me](http://iam.localtest.me)

OpenApi spec: [127.0.0.1:8011](http://127.0.0.1:8011)

Stack: [ php-cli:8.1 - swoole - symfony-6.2 ]


## Blue service

Endpoint: [blue.localtest.me](http://blue.localtest.me)

OpenApi spec: [127.0.0.1:8021](http://127.0.0.1:8021)

Stack: [ php-cli:8.1 - swoole - symfony-6.1 ]


## Red service

Endpoint: [red.localtest.me](http://red.localtest.me)

OpenApi spec: [127.0.0.1:8031](http://127.0.0.1:8031)

Stack: [ php-cli:8.1 - workerman - symfony-6.1 ]


## rabbit-mq

Web-console: [127.0.0.1:15672](http://127.0.0.1:15672)