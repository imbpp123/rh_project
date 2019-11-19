default: help

COLOR := \033[1;35m
NOCOLOR := \033[0m
APPLICATION_NAME := review-hotels

UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S),Linux)
	export REMOTE_HOST := $(shell ip -4 addr show docker0 | grep -Po 'inet \K[\d.]+')
endif
ifeq ($(UNAME_S),Darwin)
	export REMOTE_HOST := $(shell ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p' | sed -n '1p')
endif

ifneq ($(APP_IMAGE),)
	ifneq ($(APACHE_IMAGE),)
		images_are_defined = 1
	endif
endif

ifeq ($(MYSQL_IMAGE),)
	export MYSQL_IMAGE := mysql-${APPLICATION_NAME}-local
endif

ifeq ($(APP_IMAGE),)
	export APP_IMAGE := php-fpm-${APPLICATION_NAME}-local
endif

ifeq ($(APACHE_IMAGE),)
	export APACHE_IMAGE := apache-${APPLICATION_NAME}-local
endif

$(shell mkdir -p var && chmod o+w var)

build: build-apache build-app init

build-apache:
	docker build -f docker/apache/Dockerfile --pull -t ${APACHE_IMAGE} .

build-app: DOCKERFILE=docker/php-fpm/Dockerfile
build-app:
	touch .env.local
	@mkdir -p ${HOME}/.cache/composer
	docker build -f ${DOCKERFILE} --pull --target builder \
		--build-arg DEBUG_TOOLS_ENABLED=1 \
		--build-arg BUILDER_UID=`id -u` --build-arg BUILDER_GID=`id -g` \
		-t ${APP_IMAGE}-builder .
	docker run --rm --user builder \
		-v `pwd`:/home/builder/build \
		-v ${HOME}/.cache/composer:/home/builder/.cache/composer \
		-v ${SSH_AUTH_SOCK}:/ssh-agent \
		--env SSH_AUTH_SOCK=/ssh-agent \
		${APP_IMAGE}-builder \
		composer install --no-progress --prefer-dist --no-interaction --no-suggest
	docker build -f ${DOCKERFILE} --target app -t ${APP_IMAGE} .
	@echo "Run php as ${COLOR}docker run -it --rm ${APP_IMAGE} bash${NOCOLOR}"

init:
	docker network prune --force
	docker-compose down --rmi=local
	docker-compose up -d --force-recreate
	docker-compose exec mysql bash -c "while ! mysql -h localhost -u root -ppassword -e \"SELECT 1\"; do sleep 1 ;done"
	docker-compose exec php-fpm bash -c "bin/console doctrine:migrations:migrate -n"
	docker-compose exec php-fpm bash -c "bin/console doctrine:fixtures:load -n"
	docker-compose down --rmi=local

up:
	docker network prune --force
	docker-compose down --rmi=local
	docker-compose up --force-recreate

test:
	docker network prune --force
	docker-compose down --rmi=local
	docker-compose up -d --force-recreate
	docker-compose exec mysql bash -c "while ! mysql -h localhost -u root -ppassword -e \"SELECT 1\"; do sleep 1 ;done"
	docker-compose exec php-fpm bash -c "bin/console doctrine:migrations:migrate -n"
	docker-compose exec --user=root php-fpm bash -c "bin/phpunit"
