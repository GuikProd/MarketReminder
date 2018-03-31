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

cache-clear: ## Allow to clear the Symfony cache
	    rm -rf ./var/cache/*

create-schema: ## Allow to create the BDD schema
	    $(ENV_PHP) ./bin/console d:s:v
	    $(ENV_PHP) ./bin/console d:d:c --env=dev
	    $(ENV_PHP) ./bin/console d:d:c --env=test

check-schema: ## Check the mapping
check-schema: config/doctrine
	    $(ENV_PHP) ./bin/console d:s:v

fixtures_test: ## Allow to load the fixtures in the test env
fixtures_test: src/DataFixtures
	    $(ENV_PHP) ./bin/console d:f:l -n --env=test

fixtures_dev: ## Allow to load the fixtures in the dev env
fixtures_dev: src/DataFixtures
	    $(ENV_PHP) ./bin/console d:f:l -n --env=dev

phpunit: ## Launch all PHPUnit tests
phpunit: tests
	    $(ENV_PHP) vendor/bin/phpunit

behat: ## Launch all Behat tests
behat: features
	    $(ENV_PHP) vendor/bin/behat --profile default

## Varnish commands
logs: ## Allow to see the varnish logs
	    $(ENV_VARNISH) varnishlog -b

## Blackfire commands
profiler: ## Allow to profile a page using Blackfire
	    $(ENV_BLACKFIRE) blackfire curl http://172.18.0.1/$(PROFILER_URL) --samples $(PROFILER_SAMPLES)
