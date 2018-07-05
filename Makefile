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
	    $(ENV_PHP) rm -rf .env ./node_modules ./vendor

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
	     $(ENV_PHP) rm -rf ./var/cache/*

cache-warm: ## Allow to warm the cache
	    $(ENV_PHP) ./bin/console cache:warmup

translation-warm: translations
	    $(ENV_PHP) ./bin/console app:translation:warm $(CHANNEL) $(LOCALE) --env=$(ENV)

translation-dump: translations
	    $(ENV_PHP) ./bin/console app:translation:dump $(CHANNEL) $(LOCALE) --env=$(ENV)

container: ## Allow to debug the container
	    $(ENV_PHP) ./bin/console debug:container --show-private

event:
	    $(ENV_PHP) ./bin/console debug:event-dispatcher

router: ## Allow to debug the router
	    $(ENV_PHP) ./bin/console d:r

create-schema: ## Allow to create the BDD schema
	    $(ENV_PHP) ./bin/console d:d:d --force --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:d:c --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:c --env=$(ENV)

check-schema: config/doctrine
	    $(ENV_PHP) ./bin/console doctrine:schema:validate --env=$(ENV)

update-schema: ## Allow to update the schema
		$(ENV_PHP) ./bin/console d:d:d --force --env=$(ENV)
		$(ENV_PHP) ./bin/console d:d:c --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:u --dump-sql --env=$(ENV)
	    $(ENV_PHP) ./bin/console d:s:u --force --env=$(ENV)

fixtures: src/DataFixtures
	    $(ENV_PHP) ./bin/console doctrine:fixtures:load -n --env=${ENV}

doctrine-cache: ## Allow to clean the Doctrine cache
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-query
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-metadata

phpunit: tests
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/phpunit --exclude-group Blackfire,e2e tests/$(FOLDER)

phpunit-e2e: tests
	    make cache-clear
	    make update-schema ENV=test
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump messages en --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump validators en --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump session fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:dump session en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm messages fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm messages en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm validators fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm validators en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm session fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm session en --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form fr --env=test
	    $(ENV_PHP) ./bin/console app:translation:warm form en --env=test
	    $(ENV_PHP) ./bin/phpunit --group e2e

phpunit-blackfire: tests
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/phpunit --group Blackfire tests/$(FOLDER)

behat: features
	    make cache-clear
	    make update-schema ENV=test
	    make fixtures ENV=test
	    make doctrine-cache
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr
	    $(ENV_PHP) ./bin/console app:translation:dump messages en
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr
	    $(ENV_PHP) ./bin/console app:translation:dump validators en
	    $(ENV_PHP) ./bin/console app:translation:dump session fr
	    $(ENV_PHP) ./bin/console app:translation:dump session en
	    $(ENV_PHP) ./bin/console app:translation:warm messages fr
	    $(ENV_PHP) ./bin/console app:translation:warm messages en
	    $(ENV_PHP) ./bin/console app:translation:warm validators fr
	    $(ENV_PHP) ./bin/console app:translation:warm validators en
	    $(ENV_PHP) ./bin/console app:translation:warm session fr
	    $(ENV_PHP) ./bin/console app:translation:warm session en
	    $(ENV_PHP) ./bin/console app:translation:warm form fr
	    $(ENV_PHP) ./bin/console app:translation:warm form en
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
varnish_logs: ## Allow to see the varnish logs
	    $(ENV_VARNISH) varnishlog -b

## Blackfire commands
profile_php: ## Allow to profile a page using Blackfire and PHP environment
	    make cache-clear
	    make doctrine-cache ENV=prod
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr
	    $(ENV_PHP) ./bin/console app:translation:dump messages en
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr
	    $(ENV_PHP) ./bin/console app:translation:dump validators en
	    $(ENV_PHP) ./bin/console app:translation:dump session fr
	    $(ENV_PHP) ./bin/console app:translation:dump session en
	    $(ENV_PHP) ./bin/console app:translation:dump form fr
	    $(ENV_PHP) ./bin/console app:translation:dump form en
	    $(ENV_PHP) ./bin/console app:translation:warm messages fr
	    $(ENV_PHP) ./bin/console app:translation:warm messages en
	    $(ENV_PHP) ./bin/console app:translation:warm validators fr
	    $(ENV_PHP) ./bin/console app:translation:warm validators en
	    $(ENV_PHP) ./bin/console app:translation:warm session fr
	    $(ENV_PHP) ./bin/console app:translation:warm session en
	    $(ENV_PHP) ./bin/console app:translation:warm form fr
	    $(ENV_PHP) ./bin/console app:translation:warm form en
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1:8080$(URL) --samples $(SAMPLES)

profile_varnish: ## Allow to profile a page using Blackfire and Varnish environment
	    make cache-clear
	    make doctrine-cache ENV=prod
	    $(ENV_PHP) ./bin/console cache:pool:prune
	    $(ENV_PHP) ./bin/console app:translation:dump messages fr
	    $(ENV_PHP) ./bin/console app:translation:dump messages en
	    $(ENV_PHP) ./bin/console app:translation:dump validators fr
	    $(ENV_PHP) ./bin/console app:translation:dump validators en
	    $(ENV_PHP) ./bin/console app:translation:dump session fr
	    $(ENV_PHP) ./bin/console app:translation:dump session en
	    $(ENV_PHP) ./bin/console app:translation:dump form fr
	    $(ENV_PHP) ./bin/console app:translation:dump form en
	    $(ENV_PHP) ./bin/console app:translation-warm messages fr
	    $(ENV_PHP) ./bin/console app:translation-warm messages en
	    $(ENV_PHP) ./bin/console app:translation-warm validators fr
	    $(ENV_PHP) ./bin/console app:translation-warm validators en
	    $(ENV_PHP) ./bin/console app:translation-warm session fr
	    $(ENV_PHP) ./bin/console app:translation-warm session en
	    $(ENV_PHP) ./bin/console app:translation:warm form fr
	    $(ENV_PHP) ./bin/console app:translation:warm form en
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1$(URL) --samples $(SAMPLES)
