# sycton

[![Iam service CD/CI](https://github.com/zerai/sycton/actions/workflows/iam-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/iam-service.yaml)
[![Blue service CD/CI](https://github.com/zerai/sycton/actions/workflows/blue-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/blue-service.yaml)
[![Red service CD/CI](https://github.com/zerai/sycton/actions/workflows/red-service.yaml/badge.svg)](https://github.com/zerai/sycton/actions/workflows/red-service.yaml)

## How to run

Start all microservices and 'infra' related container:
````shell
docker compose --profile all up -d --wait
````

## iam service 

Endpoint: [https://iam.localtest.me](https://iam.localtest.me)

OpenApi spec: [http://swagger.iam.localtest.me](http://swagger.iam.localtest.me)

Stack: [ php-cli:8.1 - swoole - symfony-6.4 ]

How to run as a single service:
````shell
docker compose --profile iam up -d --wait
````


## Blue service

Endpoint: [https://blue.localtest.me](https://blue.localtest.me)

OpenApi spec: [http://swagger.blue.localtest.me](http://swagger.blue.localtest.me)

Stack: [ php-cli:8.1 - swoole - symfony-6.4 ]

How to run as a single service:
````shell
docker compose --profile blue up -d --wait
````

## Red service

Endpoint: [https://red.localtest.me](https://red.localtest.me)

OpenApi spec: [http://swagger.red.localtest.me](http://swagger.red.localtest.me)

Stack: [ php-cli:8.1 - workerman - symfony-6.4 ]

How to run as a single service:
````shell
docker compose --profile red up -d --wait
````

## rabbit-mq

Web-console: [http://127.0.0.1:15672](http://127.0.0.1:15672)