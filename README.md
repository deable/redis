deable / redis
==============

Simple redis client implementation for nette framework with transactions.
Thanks to this library, you can simplify your workflow with redis and [Nette Framework](https://nette.org/).

Requirements
------------

This library was developed for PHP 7.4 or newer, designed for [Nette Framework](https://nette.org/) version 3.1 or newer.

Installation
------------

The best way to install this library is using [Composer](https://getcomposer.org/):

```sh
$ composer require deable/redis
```

Usage
-----

Add extension to your application configuration: 

```yarn
extensions:
    redis: Deable\Redis\RedisExtension

redis:
    config:
        host: localhost
        port: 6379
        timeout: 5.0
        database: 0
        persistent: false
        auth: null
    autowired: false
    debugger: false
```

Contributing
------------
This is an open source, community-driven project. If you would like to contribute,
please follow the code format as used in current sources and submit a pull request.
