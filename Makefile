up:
	docker compose up -d
build:
	docker compose build --no-cache --force-rm
create-project:
	@make build
	@make up
	rm -f src/.gitignore
	docker compose exec sample.laravel.permission composer create-project --prefer-dist laravel/laravel .
	docker compose exec sample.laravel.permission php artisan key:generate
	docker compose exec sample.laravel.permission php artisan storage:link
	docker compose exec sample.laravel.permission chmod -R 777 storage bootstrap/cache
install-recommend-packages:
	docker compose exec sample.laravel.permission composer require doctrine/dbal
	@make install-packages-laravel-ide-helper
	docker compose exec sample.laravel.permission composer require --dev barryvdh/laravel-debugbar
	docker compose exec sample.laravel.permission php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
	@make install-packages-laravel-pint
	@make install-packages-larastan
init:
	docker compose up -d --build
	docker compose exec sample.laravel.permission composer install
	docker compose exec sample.laravel.permission cp .env.example .env
	docker compose exec sample.laravel.permission php artisan key:generate
	docker compose exec sample.laravel.permission php artisan storage:link
	docker compose exec sample.laravel.permission chmod -R 777 storage bootstrap/cache
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
down-v:
	docker compose down --remove-orphans --volumes
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
ps:
	docker compose ps
logs:
	docker compose logs
logs-watch:
	docker compose logs --follow
log-web:
	docker compose logs web
log-web-watch:
	docker compose logs --follow web
log-app:
	docker compose logs app
log-app-watch:
	docker compose logs --follow app
log-db:
	docker compose logs db
log-db-watch:
	docker compose logs --follow db
web:
	docker compose exec web bash
.PHONY: app
app:
	docker compose exec sample.laravel.permission bash
migrate:
	docker compose exec sample.laravel.permission php artisan migrate
seed:
	docker compose exec sample.laravel.permission php artisan db:seed
rollback-test:
	docker compose exec sample.laravel.permission php artisan migrate:fresh
	docker compose exec sample.laravel.permission php artisan migrate:refresh
tinker:
	docker compose exec sample.laravel.permission php artisan tinker
test:
	docker compose exec sample.laravel.permission php artisan test
test-coverage:
	docker compose exec sample.laravel.permission php artisan test --coverage
optimize:
	docker compose exec sample.laravel.permission php artisan optimize
optimize-clear:
	docker compose exec sample.laravel.permission php artisan optimize:clear
cache:
	docker compose exec sample.laravel.permission composer dump-autoload -o
	@make optimize
	docker compose exec sample.laravel.permission php artisan event:cache
	docker compose exec sample.laravel.permission php artisan view:cache
cache-clear:
	docker compose exec sample.laravel.permission composer clear-cache
	@make optimize-clear
	docker compose exec sample.laravel.permission php artisan event:clear
db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
redis:
	docker compose exec redis redis-cli
ide-helper:
	docker compose exec sample.laravel.permission php artisan clear-compiled
	docker compose exec sample.laravel.permission php artisan ide-helper:generate
	docker compose exec sample.laravel.permission php artisan ide-helper:meta
	docker compose exec sample.laravel.permission php artisan ide-helper:models --write
pint:
	docker compose exec sample.laravel.permission composer pint
check-pint:
	docker compose exec sample.laravel.permission composer check-pint
fix-style:
	docker compose exec sample.laravel.permission composer fix-style
check-style:
	docker compose exec sample.laravel.permission composer check-style
phpstan:
	docker compose exec sample.laravel.permission composer phpstan
install-packages-laravel-pint:
	docker compose exec sample.laravel.permission composer require laravel/pint --dev
	if type "jq" > /dev/null 2>&1; then \
		cp ./composer.json ./composer.json.tmp; \
		jq --indent 4 '.scripts |= .+{"pint": "./vendor/bin/pint -v", "check-pint": "./vendor/bin/pint --test"}' ./composer.json.tmp  > ./composer.json; \
		rm -f ./composer.json.tmp; \
	fi
install-packages-laravel-ide-helper:
	docker compose exec sample.laravel.permission composer require --dev barryvdh/laravel-ide-helper
	@make ide-helper
install-packages-larastan:
	docker compose exec sample.laravel.permission composer require --dev nunomaduro/larastan
	if type "jq" > /dev/null 2>&1; then \
		cp ./src/composer.json ./src/composer.json.tmp; \
		jq --indent 4 '.scripts |= .+{"phpstan": "./vendor/bin/phpstan analyse --xdebug"}' ./src/composer.json.tmp  > ./src/composer.json; \
		rm -f ./src/composer.json.tmp; \
	fi
