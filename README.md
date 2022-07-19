# MRIPTA Web App / Dashboard

MRIPTA Laravel web application. Uses MySQL db for relational data (users, locations and such), and MongoDB for sensors data (inserted there by the broker app).

## Locally building the dashboard

```bash
docker build -t dashboard:local .
```

## Testing it locally

Just use the docker-compose with the default config (using the dashboard:local image), alternatively use the one inside the `docker` repo to have the remaining containers also running.
Note: between builds there is a need to remove the laravel volume in order to update the code... for some reason the files already in the volume are not replaced by the ones in the container... so new code will not work until the `laravel` volume is removed. This will also delete storage (if files are uploaded).

```bash
sudo docker-compose up -d
sudo docker-compose down
docker volume rm dashboard_laravel
docker build -t dashboard:local .
sudo docker-compose up -d
```

# IMPORTANT TO DO
The dev environment must be reorganized properly...