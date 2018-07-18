SHELL := /bin/bash

all: build run

run: build
	docker-compose run --service-ports web

test: build
	  docker-compose run --service-ports web sh -c './sdktest'

build: .built .bundled

.built: Dockerfile
	docker-compose build
	touch .built

.bundled: composer.json composer.lock
	docker-compose run --rm web composer install
	touch .bundled

stop:
	docker-compose stop

restart: build
	docker-compose restart web

clean: stop
	rm -f tmp/pids/*
	docker-compose rm -f -v bundle_cache
	rm -f .bundled
	docker-compose rm -f
	rm -f .built

logs:
	docker-compose logs