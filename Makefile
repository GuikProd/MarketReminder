# Main commands
DOCKER_COMPOSE = docker-compose
DOCKER = docker
COMPOSER = $(ENV_PHP) composer

## Environments
ENV_PHP = $(DOCKER) exec marketReminder_php-fpm
ENV_VARNISH = $(DOCKER) exec marketReminder_varnish
ENV_BLACKFIRE = $(DOCKER) exec marketReminder_blackfire

## Globals commands
start: ## Allow to create the project
	    $(DOCKER_COMPOSE) up -d --build --remove-orphans --no-recreate
	    make install
	    make cache-clear

restart: ## Allow to recreate the project
	    $(DOCKER_COMPOSE) up -d --build --remove-orphans
	    make install
	    make cache-clear

stop: ## Allow to stop the containers
	    $(DOCKER_COMPOSE) stop

clean: ## Allow to delete the generated files and clean the project folder
	    rm -rf .env ./node_modules ./vendor

## PHP commands
install: ## Install the project
install: composer.json
	     $(COMPOSER) install -a -o
	     $(COMPOSER) clear-cache
	     $(COMPOSER) dump-autoload --optimize --classmap-authoritative

update: ## Update the dependencies
update: composer.lock
	     $(COMPOSER) update -a -o

require: ## Allow to install a new dependencies
	    $(COMPOSER) req $(PACKAGE) -a -o

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
	    $(ENV_PHP) ./bin/console app:translation-warm $(FILENAME) $(LOCALE)

container: ## Allow to debug the container
	    $(ENV_PHP) ./bin/console debug:container $(SERVICE) --show-private

route: ## Allow to debug the router
	    $(ENV_PHP) ./bin/console d:r

create-schema: ## Allow to create the BDD schema
	    $(ENV_PHP) ./bin/console d:s:v
	    $(ENV_PHP) ./bin/console d:d:c --env=dev
	    $(ENV_PHP) ./bin/console d:d:c --env=test

check-schema: ## Check the mapping
check-schema: config/doctrine
	    $(ENV_PHP) ./bin/console d:s:v

update-schema: ## Allow to update the schema
	    $(ENV_PHP) ./bin/console d:s:u --force

fixtures_test: ## Allow to load the fixtures in the test env
fixtures_test: src/DataFixtures
	    $(ENV_PHP) ./bin/console d:f:l -n --env=test

fixtures_dev: ## Allow to load the fixtures in the dev env
fixtures_dev: src/DataFixtures
	    $(ENV_PHP) ./bin/console d:f:l -n --env=dev

redis: ## Allow to clean the Redis cache
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-query
	    $(ENV_PHP) ./bin/console doctrine:cache:clear-metadata

phpunit: ## Launch all PHPUnit tests
phpunit: tests
	    $(ENV_PHP) vendor/bin/phpunit

phpunit-blackfire: ## Allow to launch Blackfire tests
	    $(ENV_PHP) vendor/bin/phpunit --group Blackfire

behat: ## Launch all Behat tests
behat: features
	    make check-schema
	    make fixtures_test
	    make redis
	    $(ENV_PHP) vendor/bin/behat --profile $(PROFILE)

## Tools commands
php-cs: ## Allow to use php-cs-fixer
	    $(ENV_PHP) php-cs-fixer fix $(FOLDER) --rules=@$(RULES)

deptrac: ## Allow to use the deptrac analyzer
	    $(ENV_PHP) deptrac

phpmetrics: ## Allow to launch a phpmetrics analyze
	    $(ENV_PHP) vendor/bin/phpmetrics src

## Varnish commands
logs: ## Allow to see the varnish logs
	    $(ENV_VARNISH) varnishlog -b

## Blackfire commands
profile: ## Allow to profile a page using Blackfire
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1$(URL) --samples $(SAMPLES)
