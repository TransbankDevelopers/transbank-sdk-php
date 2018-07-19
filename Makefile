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

logs:
	docker-compose logs
	