# MRIPTA Web App / Dashboard

MRIPTA Laravel web application. Uses MySQL db for relational data (users, locations and such), and MongoDB for sensors data (inserted there by the broker app).

## Locally building the dashboard

```bash
docker build -t dashboard:local .
```

## Building with GitHub Actions
A GitHub action is configured on new releases to run tests, build image and push to dockerhub. See `.github/` for details.

## Development Environment
The dev env was reorganized and now uses Laravel Sail. A docker container is build (from Ubuntu 20.04) and the project can be launcher with `sail` CLI ([see docs](https://laravel.com/docs/8.x/sail)). Also, devcontainers were added, so VSCode will automatically launch de containers and the terminal will open inside the container ([see docs](https://code.visualstudio.com/docs/devcontainers/tutorial)) This means `sail` cli is not needed if commands are executed in the VSCode terminal. For details see files:
* docker/7.4/Dockerfile
* docker-compose.yml
* .devcontainer/
* .vscode/

Other important notes:
* Xdebug not yet working
* MySQL 8 in use (instead of 5.7 previously - test!)
* The `.env.example` must be copied to `.env` and edited as needed
* Initially no app key exists (generate as needed)
* Migrations might also need to be run (`php artisan migrate`?)
* Same with seed data (`php artisan db:seed`)
  * Run `php artisan migrate:fresh --seed` to drop d and run migrations + seeds from the start