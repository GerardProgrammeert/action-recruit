project = template # change this for your project name

in:
	docker exec -it "$(project)-php-fpm-1" /bin/bash

up:
	docker-compose up -d

down:
	docker-compose down

rebuild:
	docker-compose down
	docker-compose build
	docker-compose up -d

tail:
	@docker compose logs --follow

install-laravel:
	composer create-project laravel/laravel
	mv laravel/* laravel/.* .
	rm -d laravel

laravel-chmod:
	@chmod o+w ./storage/ -R

git-rm-untracked:
	git clean -fd
	git clean -fx
	rm -rf vendor public bootstrap
	rm -f composer.json composer.lock


