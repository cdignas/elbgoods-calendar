# Elbgoods Calendar API

A simplified calendar application for a larger platform. Link to complete description [README.md](src/README.md)

Using a simplified Docker Compose workflow that sets up a LEMP network of containers for local Laravel development. You can see repo that based on [here](https://github.com/aschmelyun/docker-compose-laravel).
The following are built for our web server, with their exposed ports detailed:

- **nginx** - `:80`
- **mysql** - `:3306`
- **php** - `:9000`
- **redis** - `:6379`
- **mailhog** - `:8025`

### Build App
To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running 

`docker-compose up -d --build app`.

navigate to src folder copy `.env.example` and rename to `.env`

### Start App

Three additional containers are included that handle Composer, NPM, and Artisan commands *without* having to have these platforms installed on your local computer. Use the following command examples from your project root, modifying them to fit your particular use case.

- `docker-compose run --rm composer update`
- `docker-compose run --rm npm install`
- `docker-compose run --rm npm run dev`
- `docker-compose run --rm artisan migrate:fresh --seed`

### Swagger Doc

Link to [Swagger Doc](http://localhost/docs/swagger) for Testing Endpoints in Frontend

### Unit Tests

Migrated db with `AppointmentSeeders` is required for unit tests.

`docker-compose run --rm artisan migrate:fresh --seed`

`docker-compose run --rm artisan test --filter=AppointmentControllerTest`
