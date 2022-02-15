## NBA match simulator

NBA match simulator is built on laravel with Docker.

### Running the project
The nessesary binaries and software for this simulator are packaged with Docker, the infrastructure is defined in the `docker-compose.yml` file. Command to build and run the project `docker-compose up -d`, this command will build the containers when running for the first time.

### Configuration
The configuration for PHP, Nginx and MySQL is defined in folders with corresponding names. The root password for MySQL is defined in the `docker-compose.yml` file using the environment variable `MYSQL_ROOT_PASSWORD`. After running the containers the user needs to update the DB config in the laravel project `.env` file. For more info on specific configuration of with Docker Compose read [How To Set Up Laravel, Nginx, and MySQL with Docker Compose](https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose).

### Installing Composer dependencies
Use the following command to install the composer dependencies.
- `docker exec -it app compsoer install`

### Migrating the DB
Use the follwing command to migrate and seed the db after configuring connection with the DB.
- `docker exec -it app php artisan migrate`
- `docker exec -it app php artisan db:seed`

### Creating fixture and Simulating NBA matches
Use the following commands to create the fixtures and run the simulations.
- `docker exec -it app php artisan fixture:create`
- `docker exec -it app php artisan simulate:match`

After starting the simulation visit [Localhost](http://localhost) in your browser.

### Views
The simulation updates the following tables.
- The live match update every 5 seconds
- The leaderboard every 4 minutes
- The fixture (upcoming matches) table every 4 minutes
