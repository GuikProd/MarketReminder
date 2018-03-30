install: ## Install the project
install: composer.json
	     composer install -a -o

update: ## Update the dependencies
update: composer.lock
	    composer update -a -o

check-schema: ## Check the mapping
check-schema: config/doctrine
	    ./bin/console d:s:v

fixtures_test: ## Allow to load the fixtures in the test env
fixtures_test: src/DataFixtures
	    ./bin/console d:f:l -n --env=test

fixtures_dev: ## Allow to load the fixtures in the dev env
fixtures_dev: src/DataFixtures
	    ./bin/console d:f:l -n --env=dev

behat: ## Launch all Behat tests
phpunit: tests
	    vendor/bin/phpunit

behat: ## Launch all Behat tests
behat: features
	    vendor/bin/behat --profile default
