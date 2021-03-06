# Slim Framework 4 Skeleton Application

[![Coverage Status](https://coveralls.io/repos/github/slimphp/Slim-Skeleton/badge.svg?branch=master)](https://coveralls.io/github/slimphp/Slim-Skeleton?branch=master)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application. This application uses the latest Slim 4 with Slim PSR-7 implementation and PHP-DI container implementation. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

```bash
composer create-project slim/slim-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.



/////////////////////
$ composer create-project slim/slim-skeleton:dev-master API_MPOS
$ php -S localhost:8080 -t public public/index.php

- install ubuntu for window in store

$ sudo apt-get update
$ sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add
$ sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu  $(lsb_release -cs)  stable"

$ sudo gpasswd -a $USER docker
$ sudo service docker start



docker pull hello-world

/////////////// docker-compose ////////////////
$ sudo apt-get update
$ sudo apt-get upgrade
$ sudo apt install curl
$ sudo curl -L "https://github.com/docker/compose/releases/download/1.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose

///////////////// docker 
docker rmi $(docker images -a -q)

///////////////// run 
$ docker volume create mysql_admin
$ docker volume create mysql_config_admin
$ docker volume create mysql_member
$ docker volume create mysql_config_member
$ docker network create mpos_net
$ docker network create mpos_net1
$ docker run --rm -d -v mysql:/var/lib/mysql -v mysql_config:/etc/mysql -p 3306:3306 --network mpos_net --name mpos_mysql_admin -e MYSQL_ROOT_PASSWORD=p@ssw0rd1 mysql
$ docker exec -ti mpos_mysql_admin mysql -u root -p
$ docker run --rm -d -v mysql1:/var/lib/mysql -v mysql_config1:/etc/mysql -p 3308:3306 --network mpos_net1 --name mpos_mysql_member -e MYSQL_ROOT_PASSWORD=p@ssw0rd2 mysql
$ docker exec -ti mpos_mysql_member mysql -u root -p

////////////////// check extend php
$ docker run --rm php:8.0.0-fpm-alpine php -m


//// add to
#   s l i m - a p i 
 
 

////////////////// if error
???add git  => use ssh