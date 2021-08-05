start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm install
	npm run dev

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

test-coverage:
	composer exec --verbose phpunit -- --coverage-clover build/logs/clover.xml

deploy:
	git push heroku

lint:
	composer run-script phpcs

lint-fix:
	composer run-script phpcbf

fresh-seed:
	php artisan migrate:fresh
	php artisan db:seed --class=DomainSeeder
	php artisan db:seed --class=DomainChecksSeeder