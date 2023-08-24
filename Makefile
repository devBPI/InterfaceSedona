################################################################################
## Path, commands and variables declarations
################################################################################
# Extract project name from composer.json[name]
COMPOSER_PROJECT_NAME = $(shell awk 'BEGIN{FS=":"} $$0 ~ /\s*"name"/ {print $$2}' composer.json | head -n1 | cut -d'"' -f2)
COMPOSER_PROJECT_VERSION = $(shell awk 'BEGIN{FS=":"} $$0 ~ /\s*"version"/ {print $$2}' composer.json | cut -d'"' -f2)
COMPOSER_PROJECT_DESCRIPTION = $(shell awk 'BEGIN{FS=":"} $$0 ~ /\s*"description"/ {print $$2}' composer.json | cut -d'"' -f2)
# client/project-module
CLIENT_NAME = $(firstword $(subst /, ,${COMPOSER_PROJECT_NAME}))
PROJECT_NAME = $(firstword $(subst -, ,$(lastword $(subst /, ,$(COMPOSER_PROJECT_NAME)))))
MODULE_NAME = $(lastword $(subst -, ,$(lastword $(subst /, ,$(COMPOSER_PROJECT_NAME)))))
# GAV
GROUP_ID = fr.$(CLIENT_NAME).$(PROJECT_NAME)
ARTIFACT_ID = $(PROJECT_NAME)-$(MODULE_NAME)
VERSION = $(subst -RC,-rc.,$(subst -dev,-SNAPSHOT,${COMPOSER_PROJECT_VERSION}))
# Output
PACKAGE_NAME ?= ${ARTIFACT_ID}.${VERSION}.tgz
DOCKER_TAG ?= ${CI_REGISTRY_IMAGE}/site:latest

APP_ENV ?= prod
NOW = $(shell date +"%d/%m/%Y-%H:%M:%S")
BUILD_DIR = build

UID ?= $(shell id -u)
DOCKER := $(shell which docker 2> /dev/null)
# FIG = original name of docker-compose tool
FIG := $(shell which docker-compose 2> /dev/null)
# Docker image name labelled in Dockerfile
PROJECT_IMAGE_NAME := $(subst -,/,$(COMPOSER_PROJECT_NAME))
PHP_IMAGE_NAME := $(shell awk 'NR==1{print $$2}' Dockerfile )

IN_DOCKER = $(shell expr `cat /proc/1/sched | head -n 1 | grep -cE 'init|systemd'` = 0)

#
TAR_CAN_EXCLUDE_VCS = $(shell expr `tar --version | grep ^tar | sed 's/^.* //g'` \>= 1.28)

# PHP
PHP_BIN ?= $(shell which php7.4 2> /dev/null || which php 2> /dev/null || echo 'php')
# Composer options
COMPOSER ?= $(PHP_BIN) -d memory_limit=-1 $(shell test -f composer.phar && echo "composer.phar" || which composer 2> /dev/null)
AUTOLOAD_OPTIONS ?= -o -a
CINSTALL_OPTIONS ?= --no-interaction --prefer-dist --no-progress

# Symfony options
SF_VERSION := $(shell expr `grep '"symfony/symfony"' composer.json | cut -d':' -f2 | grep -oe "[[:digit:]].[[:digit:]]" | cut -d. -f1`)
ifeq "$(SF_VERSION)" "2"
CONSOLE := $(PHP_BIN) app/console
VAR_DIR = $(CURDIR)/app
else
CONSOLE := $(PHP_BIN) bin/console
VAR_DIR = $(CURDIR)/var
endif

CACHE_DIR = $(VAR_DIR)/cache
LOG_DIR = $(VAR_DIR)/logs

################################################################################
## .DEFAULT
################################################################################
default: c-install show-vars package
.PHONY: default

install: .env c-install cc db-init assets
.PHONY: install

show-vars:
	@printf "PHP BINARY: \033[32m${PHP_BIN}\033[39m\n"
	@printf "COMPOSER PROJECT: \033[32m${COMPOSER_PROJECT_NAME}\033[39m\n"
	@printf "COMPOSER DESCRIPTION: \033[32m${COMPOSER_PROJECT_DESCRIPTION}\033[39m\n"
	@printf "CLIENT: \033[32m${CLIENT_NAME}\033[39m\n"
	@printf "PROJECT: \033[32m${PROJECT_NAME}\033[39m\n"
	@printf "MODULE: \033[32m${MODULE_NAME}\033[39m\n"
	@printf "DOCKER TAG: \033[32m${DOCKER_TAG}\033[39m\n"

	@printf "\nMaven G.A.V. :\n"
	@printf "GROUP: \033[32m${GROUP_ID}\033[39m\n"
	@printf "ARTIFACT: \033[32m${ARTIFACT_ID}\033[39m\n"
	@printf "VERSION: \033[32m${VERSION}\033[39m\n"
	@printf "ARTIFACT FILE: \033[32m${PACKAGE_NAME}\033[39m\n"

.PHONY: show-vars

show-var-CLIENT_NAME:
	echo '${CLIENT_NAME}' | tr 'a-z' 'A-Z'
.PHONY: show-var-%

show-var-%:
	@printf "${$*}\n"
.PHONY: show-var-%

env-%:
	$(eval APP_ENV=$*)
ifneq (${APP_ENV}, "prod")
	$(eval AUTOLOAD_OPTIONS = -o)
	$(eval CINSTALL_OPTIONS = --no-interaction)
endif
	@printf "App mode: \033[32m${APP_ENV}\033[39m.\n"
.PHONY: env-%

# génération du fichier sonar a partir du fichier composer
sonar-project.properties:
	sed -E \
		-e 's/sonar.projectKey=[^\n]+/sonar.projectKey=${GROUP_ID}:${ARTIFACT_ID}/g' \
		-e 's/sonar.projectName=[^\n]+/sonar.projectName=[$(shell echo -n ''${CLIENT_NAME}'' | tr "a-z" "A-Z")] ${COMPOSER_PROJECT_DESCRIPTION}/g'\
		-e 's/sonar.projectVersion=[^\n]+/sonar.projectVersion=${VERSION}/g'\
		.deploy/sonar-project.dist.properties > sonar-project.properties

show-mode:
	@printf "App mode: \033[32m${APP_ENV}\033[39m.\n"
.PHONY: show-mode

# clean des caches applicatif
clean:
	rm -rf $(CACHE_DIR)/${APP_ENV}/
	rm -rf $(LOG_DIR)/${APP_ENV}*.log
.PHONY: clean

# clean des caches applicatif + fichier de generation
clean-all: clean
	rm -rf $(BUILD_DIR)
	rm -rf package_info.json
.PHONY: clean-all

$(BUILD_DIR):
	mkdir $@

# génération du fichier de version
package_info.json:
	echo "{\"date_version\":\"${NOW}\",\"tag\":\"${CI_COMMIT_TAG}\",\"project_url\":\"${CI_PROJECT_URL}\", \"sha\":\"${CI_COMMIT_SHA}\"}" | tee package_info.json

.env:
	@printf "Make dotenv files \n"
	cp -n .env.dist .env

dotenv-make: .env
	chmod 600 .env
.PHONY: dotenv-make

dev-dotenv: dotenv-make
	cat .deploy/dev/env.dist >> .env
.PHONY: dotenv-make

dotenv-clear:
	@printf "Clear dotenv files \n"
	rm -rf .env
	touch .env
	chmod 600 .env
.PHONY: dotenv-clear

################################################################################
## Docker Compose commands (for development)
################################################################################

get-uid:
ifdef UID
	@export UID
	@$(shell export UID)
else
	@$(error Unable to detect current UID, try to provide it as argument: `make UID=<value> <target>`)
endif
.PHONY: get-uid

show-uid: get-uid
	@printf "Detected UID : \033[32m$(UID)\033[39m.\n"
.PHONY: show-uid

stack: get-uid
ifdef FIG
	$(FIG) -f docker-compose.yaml -f .deploy/docker-compose.${APP_ENV}.yaml up -d
else
	@$(error Missing docker-compose)
endif
.PHONY: stack

stack-shell: get-uid stack
ifdef FIG
	$(FIG) -f docker-compose.yaml -f .deploy/docker-compose.${APP_ENV}.yaml exec --user ${UID} app bash
else
	@$(error Missing docker-compose)
endif
.PHONY: stack-shell

stack-%: get-uid
ifdef FIG
	$(eval ACTION=$*)
	$(FIG) -f docker-compose.yaml -f .deploy/docker-compose.${APP_ENV}.yaml ${ACTION} ${STACK_OPTIONS}
else
	@$(error Missing docker-compose)
endif
.PHONY: stack-%

################################################################################
##	Composer commands
################################################################################

autoload:
ifndef COMPOSER
	@$(error Missing composer)
endif
	$(COMPOSER) dump-autoload $(AUTOLOAD_OPTIONS)
.PHONY: autoload

c-install:
ifndef COMPOSER
	@$(error Missing composer)
endif
	$(COMPOSER) install $(CINSTALL_OPTIONS) $(AUTOLOAD_OPTIONS)
.PHONY: c-install

c-update:
ifndef COMPOSER
	@$(error Missing composer)
endif
	$(COMPOSER) update $(CINSTALL_OPTIONS) $(AUTOLOAD_OPTIONS)
.PHONY: c-update

################################################################################
##	Symfony commands
################################################################################
## Inspired by Symfony 4 / Symfony Flex

cache-clear:
ifdef CONSOLE
	$(CONSOLE) --env=${APP_ENV} cache:clear --no-warmup
#	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-metadata
#	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-query
#	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-result
else
	rm -rf $(CACHE_DIR)/${APP_ENV}/*
endif
	@printf "Don't forget to run \033[32mmake cache-warmup\033[39m.\n"
.PHONY: cache-clear

cache-warmup: cache-clear
ifdef CONSOLE
	$(CONSOLE) --env=${APP_ENV} cache:warmup
else
	@printf "Cannot warmup the cache (needs symfony/console)\n"
endif
.PHONY: cache-warmup

assets:
ifdef CONSOLE
	@if [ ! "$(APP_ENV)" = "dev" ]; \
	then \
		$(CONSOLE) --env=${APP_ENV} assets:install ;\
	else \
		$(CONSOLE) --env=${APP_ENV} assets:install --symlink  ;\
	fi
	$(CONSOLE) --env=${APP_ENV} fos:js-routing:dump --format=json --target=assets/js/fos_js_routes.json
else
	@printf "Cannot install assets (needs symfony/console)\n"
endif
.PHONY: assets

cc: autoload cache-clear cache-warmup
.PHONY: cc

################################################################################
##	Tests
################################################################################

functional-tests:
	@printf "=== functional tests ============================= \n"
	$(COMPOSER) test:acceptance
.PHONY: unit-tests

unit-tests:
	@printf "=== unit tests ============================= \n"
	$(COMPOSER) test:unit
.PHONY: unit-tests

code-analysis:
	@printf "=== code analysis ============================= \n"
	$(COMPOSER) quality
.PHONY: code-analysis

fixtures:
	@printf "=== Load fixtures ============================= \n"
	#php bin/console doctrine:fixtures:load --append
.PHONY: fixtures


tests: clean install fixtures code-analysis functional-tests
.PHONY: tests

################################################################################
##	Dev commands
################################################################################
db-create:
	$(CONSOLE) --env=$(APP_ENV) doctrine:database:create --if-not-exists
.PHONY: db-create

db-update:
	$(CONSOLE) --env=$(APP_ENV) doctrine:migrations:migrate --no-interaction --allow-no-migration -v
.PHONY: db-update

db-populate:
	@if [ "$(APP_ENV)" = "prod" ]; then echo "You can not do that in production mode"; exit 1 ; fi
.PHONY: db-populate

db-drop:
	@if [ "$(APP_ENV)" = "prod" ]; then echo "You can not do that in production mode"; exit 1 ; fi
	$(CONSOLE) --env=dev doctrine:database:drop --if-exists --force
.PHONY: db-drop

db-reset: db-drop db-create db-update db-populate
	@if [ "$(APP_ENV)" = "prod" ]; then echo "You can not do that in production mode"; exit 1 ; fi
.PHONY: db-reset

db-init: db-create db-update
	@if [ ! -f $(VAR_DIR)/.db-init ]; then \
		date +%FT%H:%M:%S > $(VAR_DIR)/.db-init ; \
	fi
.PHONY: db-init


################################################################################
##	CI/CD Config Build
################################################################################

package: $(BUILD_DIR) package_info.json c-install assets dotenv-clear
	@printf "Building ${BUILD_DIR}/${PACKAGE_NAME}\n"
	tar --ignore-failed-read --exclude-from=./.deploy/.package-ignore -czf ${BUILD_DIR}/${PACKAGE_NAME} .
.PHONY: package

image: package_info.json
ifdef DOCKER
	$(DOCKER) build --compress -t ${DOCKER_TAG} .
else
	@$(error Missing docker)
endif
.PHONY: image


################################################################################
##	Project & Dependency install command
################################################################################

install-wkhtmltopdf:
	@if ! [ -x "$(command -v wkhtmltopdf)" ]; then \
	  echo "\033[30;48;5;82m >  Install wkhtmltopdf and xvfbt ------------------------------------------------------------ \033[0m"; \
	  apt-get update; \
	  apt-get install -y xvfb wget openssl libxrender-dev libx11-dev libxext-dev libfontconfig1-dev libfreetype6-dev fontconfig; \
	  wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz -O /tmp/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz; \
	  tar xvf /tmp/wkhtmltox*.tar.xz -C /tmp; \
	  mv /tmp/wkhtmltox/bin/wkhtmlto* /usr/bin/; \
	  ln -nfs /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf; \
	  echo "\033[30;48;5;82m > Install wkhtmltopdf and xvfbt done -------------------------------------------------------- \033[0m"; \
	else \
	  echo "\033[30;48;5;82m > wkhtmltopdf allredy install --------------------------------------------------------------- \033[0m"; \
	fi
.PHONY: install-wkhtmltopdf
