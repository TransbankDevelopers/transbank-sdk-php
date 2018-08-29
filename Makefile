SHELL := /bin/bash

all: build run

run: build
	docker-compose run --rm web

test: build
	docker-compose run --rm web

build: .built .bundled

.built: Dockerfile
	docker-compose build
	touch .built

.bundled: composer.json composer.lock
	docker-compose run --rm web composer install
	touch .bundled

logs:
	docker-compose logs

clean:
	docker-compose rm
	rm .built
	rm .bundled
