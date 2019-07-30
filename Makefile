################################################################################
## Path, commands and variables declarations
################################################################################
# Extract project name from composer.json[name]
COMPOSER_PROJECT_NAME = $(shell awk 'BEGIN{FS=":"} $$0 ~ /\s*"name"/ {print $$2}' composer.json | head -n1 | cut -d'"' -f2)
COMPOSER_PROJECT_VERSION = $(shell awk 'BEGIN{FS=":"} $$0 ~ /\s*"version"/ {print $$2}' composer.json | cut -d'"' -f2)
# client/project-module
CLIENT_NAME = $(firstword $(subst /, ,$(COMPOSER_PROJECT_NAME)))
PROJECT_NAME = $(firstword $(subst -, ,$(lastword $(subst /, ,$(COMPOSER_PROJECT_NAME)))))
MODULE_NAME = $(lastword $(subst -, ,$(lastword $(subst /, ,$(COMPOSER_PROJECT_NAME)))))
# GAV
GROUP_ID = fr.$(CLIENT_NAME).$(PROJECT_NAME)
ARTIFACT_ID = $(PROJECT_NAME)-$(MODULE_NAME)
VERSION = $(subst -dev,-SNAPSHOT,${COMPOSER_PROJECT_VERSION})
# Output
PACKAGE_NAME ?= ${ARTIFACT_ID}.${VERSION}.tgz
DOCKER_TAG ?= registry.sedona.fr/bpi/catalogue/bpi-catalogue/site:latest

APP_ENV ?= prod
NOW = $(shell date +"%Y%m%d-%H%M%S")
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
PHP_BIN ?= $(shell which php 2> /dev/null || echo 'php')
# Composer options
COMPOSER ?= $(PHP_BIN) $(shell which composer 2> /dev/null)
AUTOLOAD_OPTIONS ?= -o -a
CINSTALL_OPTIONS ?= --no-interaction --prefer-dist

PHPSTAN_LEVEL ?= 5

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

install: c-install cc db-init assets
.PHONY: install

show-vars:
	@printf "PHP BINARY: \033[32m${PHP_BIN}\033[39m\n"
	@printf "COMPOSER PROJECT: \033[32m${COMPOSER_PROJECT_NAME}\033[39m\n"
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


show-mode:
	@printf "App mode: \033[32m${APP_ENV}\033[39m.\n"
.PHONY: show-mode

clean:
	rm -rf $(CACHE_DIR)/${APP_ENV}/
	rm -rf $(LOG_DIR)/${APP_ENV}*.log
	rm -rf package_info.json
.PHONY: clean

clean-all: clean
	rm -rf $(BUILD_DIR)
.PHONY: clean-all

$(BUILD_DIR):
	mkdir $@

package_info.json:
	echo "{\"date_version\":\"${NOW}\",\"tag\":\"${CI_COMMIT_TAG}\",\"project_url\":\"${CI_PROJECT_URL}\", \"sha\":\"${CI_COMMIT_SHA}\"}" | tee package_info.json

.env:
	cp -n .env.dist .env
	chmod 600 .env

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
	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-metadata
	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-query
	$(CONSOLE) --env=${APP_ENV} doctrine:cache:clear-result

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
else
	@printf "Cannot install assets (needs symfony/console)\n"
endif
.PHONY: assets

cc: autoload cache-clear cache-warmup
.PHONY: cc

################################################################################
##	Tests
################################################################################

unit-tests:
	vendor/bin/phpunit
.PHONY: unit-tests

code-analysis:
	vendor/bin/phpstan analyze --level $(PHPSTAN_LEVEL) src
.PHONY: code-analysis

security-check:
	$(CONSOLE) security:check
.PHONY: security-check

behat-w3c:
	vendor/bin/behat -f progress features/w3c/
.PHONY: behat-ce

tests: unit-tests code-analysis security-check
.PHONY: tests

################################################################################
##	Dev commands
################################################################################
db-create:
	$(CONSOLE) --env=$(APP_ENV) doctrine:database:create --if-not-exists
.PHONY: db-create

db-update:
	$(CONSOLE) --env=$(APP_ENV) doctrine:migrations:migrate --no-interaction --allow-no-migration
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

package: $(BUILD_DIR) package_info.json c-install
	@printf "Building ${BUILD_DIR}/${PACKAGE_NAME}\n"
	tar --ignore-failed-read --exclude-from=./.package-ignore -czf ${BUILD_DIR}/${PACKAGE_NAME} .
.PHONY: package

image: package_info.json
ifdef DOCKER
	$(DOCKER) build --compress -t ${DOCKER_TAG} .
else
	@$(error Missing docker)
endif
.PHONY: image
