# Main commands
DOCKER_COMPOSE = docker-compose
DOCKER = docker
COMPOSER = $(ENV_PHP) composer

## Environments
ENV_PHP = $(DOCKER) exec marketReminder_php-fpm
ENV_NODE = $(DOCKER) exec marketReminder_nodejs
ENV_VARNISH = $(DOCKER) exec marketReminder_varnish
ENV_BLACKFIRE = $(DOCKER) exec marketReminder_blackfire

## Globals commands
start: ## Allow to create the project
		$(DOCKER_COMPOSE) build --no-cache
	    $(DOCKER_COMPOSE) up -d --build --remove-orphans --force-recreate
	    make install
	    make cache-clear

restart: ## Allow to recreate the project
	    $(DOCKER_COMPOSE) up -d --build --remove-orphans --no-recreate
	    make install
	    make cache-clear

stop: ## Allow to stop the containers
	    $(DOCKER_COMPOSE) stop

clean: ## Allow to delete the generated files and clean the project folder
	    rm -rf .env ./node_modules ./vendor

## PHP commands
install: composer.json
	     $(COMPOSER) install -a -o
	     $(COMPOSER) clear-cache
	     $(COMPOSER) dump-autoload --optimize --classmap-authoritative

update: composer.lock
	     $(COMPOSER) update -a -o

require: ## Allow to install a new dependencies
	    $(COMPOSER) req $(PACKAGE) -a -o

require-dev:
	    $(COMPOSER) req --dev $(PACKAGE) -a -o

remove: ## Allow to remove a dependencie
	    $(COMPOSER) remove $(PACKAGE) -a -o

autoload: ## Allow to dump the autoload
	    $(COMPOSER) dump-autoload -a -o

## Symfony commands
cache-clear: ## Allow to clear the cache
	    rm -rf ./var/cache/*

cache-warm: ## Allow to warm the cache
	    $(ENV_PHP) ./bin/console cache:warmup

translation: ## Allow to warm the translation
	    $(ENV_PHP) ./bin/console app:translation-warm $(CHANNEL) $(LOCALE) --env=$(ENV)

container: ## Allow to debug the container
	    $(ENV_PHP) ./bin/console debug:container --show-private

event:
	    $(ENV_PHP) ./bin/console debug:event-dispatcher

route: ## Allow to debug the router
	    $(ENV_PHP) ./bin/console d:r

create-schema: ## Allow to create the BDD schema
	    $(ENV_PHP) ./bin/console d:s:v
	    $(ENV_PHP) ./bin/console d:d:c --env=dev
	    $(ENV_PHP) ./bin/console d:d:c --env=test

check-schema: config/doctrine
	    $(ENV_PHP) ./bin/console doctrine:schema:validate

update-schema: ## Allow to update the schema
	    $(ENV_PHP) ./bin/console d:s:u --dump-sql
	    $(ENV_PHP) ./bin/console d:s:u --force

fixtures: src/DataFixtures
	    $(ENV_PHP) ./bin/console doctrine:fixtures:load -n --env=${ENV}

doctrine-cache: ## Allow to clean the Doctrine cache
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-query
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-metadata

redis-cache: ## Allow to clean the Redis cache
	    $(ENV_PHP) ./bin/console redis:flushall -n

phpunit: tests
	    $(ENV_PHP) ./bin/phpunit --exclude-group Blackfire tests/$(FOLDER)


phpunit-functional: tests
	    make check-schema
	    make fixtures ENV=test
	    make doctrine-cache
	    make translation CHANNEL=messages LOCALE=fr
	    make translation CHANNEL=messages LOCALE=en
	    make translation CHANNEL=validators LOCALE=fr
	    make translation CHANNEL=validators LOCALE=fr
	    $(ENV_PHP) ./bin/phpunit --group Functional tests/$(FOLDER)

phpunit-blackfire: tests
	    $(ENV_PHP) ./bin/phpunit --group Blackfire tests/$(FOLDER)

behat: features
	    make check-schema
	    make fixtures ENV=test
	    make doctrine-cache
	    make translation CHANNEL=messages LOCALE=fr ENV=test
	    make translation CHANNEL=messages LOCALE=en ENV=test
	    make translation CHANNEL=validators LOCALE=fr ENV=test
	    make translation CHANNEL=validators LOCALE=fr ENV=test
	    make translation CHANNEL=session LOCALE=fr ENV=test
	    make translation CHANNEL=session LOCALE=fr ENV=test
	    $(ENV_PHP) vendor/bin/behat --profile $(PROFILE)

## Tools commands
php-cs: ## Allow to use php-cs-fixer
	    $(ENV_PHP) php-cs-fixer fix $(FOLDER) --rules=@$(RULES)

deptrac: ## Allow to use the deptrac analyzer
	    $(ENV_PHP) deptrac

phpmetrics: ## Allow to launch a phpmetrics analyze
	    $(ENV_PHP) vendor/bin/phpmetrics src

## NodeJS commands
watch: ## Allow to use Encore to watch the asssets
	    $(ENV_NODE) yarn watch

production: ## Allow to build the assets for a production usage.
	    $(ENV_NODE) yarn build

yarn_add_prod: ## Allow to add a new package in the "prod" env
	    $(ENV_NODE) yarn add $(PACKAGE)

yarn_add_dev: ## Allow to add a new package in the "dev" env
	    $(ENV_NODE) yarn add --dev $(PACKAGE)

## Varnish commands
logs: ## Allow to see the varnish logs
	    $(ENV_VARNISH) varnishlog -b

## Blackfire commands
profile_php: ## Allow to profile a page using Blackfire and PHP environment
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1:8080$(URL) --samples $(SAMPLES)

profile_varnish: ## Allow to profile a page using Blackfire and Varnish environment
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1$(URL) --samples $(SAMPLES)
