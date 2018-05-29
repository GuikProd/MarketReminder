# MarketReminder

The source code of the web application/API used for MarketReminder mobile application. 

## Build

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/06883408-e402-40da-842e-724eadbde07b/mini.png)](https://insight.sensiolabs.com/projects/06883408-e402-40da-842e-724eadbde07b)
[![pipeline status](https://gitlab.com/GuikProd/MarketReminder/badges/master/pipeline.svg)](https://gitlab.com/GuikProd/MarketReminder/commits/master)
[![coverage report](https://gitlab.com/GuikProd/MarketReminder/badges/master/coverage.svg)](https://gitlab.com/GuikProd/MarketReminder/commits/master)
[![Build Status](https://travis-ci.org/Guikingone/MarketReminder.svg?branch=master)](https://travis-ci.org/Guikingone/MarketReminder)
[![CircleCI](https://circleci.com/gh/GuikProd/MarketReminder.svg?style=svg)](https://circleci.com/gh/GuikProd/MarketReminder)
[![Maintainability](https://api.codeclimate.com/v1/badges/0975d1e66031b5235e08/maintainability)](https://codeclimate.com/github/Guikingone/MarketReminder/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d2ba90dea73e4225ba366d2495391865)](https://www.codacy.com/app/Guikingone/MarketReminder?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Guikingone/MarketReminder&amp;utm_campaign=Badge_Grade)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Guikingone/MarketReminder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Guikingone/MarketReminder/?branch=master)

## Usage

Once you've installed Docker, time to build the project.

This project use Docker environment files in order to allow the configuration according to your needs,
this way, you NEED to define a .env file in order to launch the build.

**_In order to perform better, Docker can block your dependencies installation and return an error
or never change your php configuration, we recommend to delete all your images/containers
before building the project_**

```bash
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
docker rmi $(docker images -a -q) -f
```

**Note that this command can take several minutes before ending**

Once this is done, let's build the project.

```bash
cp .env.dist .env
```

Update the information linked to Docker then use Docker-Compose :

```bash
make start
```

Then you must use Composer in order to launch the application :

```bash
docker exec -it project_php-fpm sh

# Use Composer inside the container for better performances.
composer install
composer clear-cache
composer dump-autoload --optimize --classmap-authoritative

# Configure BDD
./bin/console d:s:c # for classic users

# Fixtures
./bin/console d:f:l -n
```

Once this is done, access the project via your browser :

- Dev :

```
http://localhost:port/
```

**For the production approach, you must update the .env file and change the APP_ENV and APP_DEBUG keys.**

- Prod :

```
http://localhost:port/
```

If you need to perform some tasks:

```bash
make // See the PHP commands
```

Once in the container:

```bash
# Example for clearing the cache
make cache-clear
```

**Please note that you MUST open a second terminal in order to keep git ou other commands line outside of Docker**

### PHP CLI

```bash
cd core
php bin/console s:r || ./bin/console s:r || make serve
```

Then access the project via your browser:

```
http://localhost:8000
```

**The commands listed before stay available and needed for this approach**


### Tests

This project use PHPUnit, Behat and Blackfire in order to test and validate his internal logic, 
here the listing of available commands for testing purpose:

```bash
make phpunit FOLDER=desiredfolder

make phpunit-blackfire FOLDER=desiredfolder

make behat
```

## Contributing 

See [Contributing](contributing/contribution.md)
