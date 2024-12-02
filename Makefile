APP_CONTAINER=app
DOCKER_COMPOSE=docker-compose
EXEC=$(DOCKER_COMPOSE) exec $(APP_CONTAINER)

up:
	@$(DOCKER_COMPOSE) up -d
down:
	@$(DOCKER_COMPOSE) down
restart:
	@$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d
logs:
	@$(DOCKER_COMPOSE) logs -f $(APP_CONTAINER)

migrate:
	@$(EXEC) php artisan migrate
seed:
	@$(EXEC) php artisan db:seed
migrate-refresh:
	@$(EXEC) php artisan migrate:refresh --seed

config-cache:
	@$(EXEC) php artisan config:cache
route-cache:
	@$(EXEC) php artisan route:cache
view-cache:
	@$(EXEC) php artisan view:cache

clear-caches:
	@$(EXEC) php artisan cache:clear
	@$(EXEC) php artisan config:clear
	@$(EXEC) php artisan route:clear
	@$(EXEC) php artisan view:clear

make-model:
	@$(EXEC) php artisan make:model $(model)
make-controller:
	@$(EXEC) php artisan make:controller $(controller)
make-migration:
	@$(EXEC) php artisan make:migration $(migration)
make-seeder:
	@$(EXEC) php artisan make:seeder $(seeder)

test:
	@$(EXEC) php artisan test

composer-install:
	@$(EXEC) composer install