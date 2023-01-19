<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://carefer.co/wp-content/uploads/2022/08/logo.png" width="400" alt="Laravel Logo"></a></p>


## About Carefer Backend Challenge

This is the working repository for the backend challenge for a Senior Backend Enginner position at Carefer.


## Dependencies

This project is set up to be built with docker instead of installing any software you don't need on your machine, you should have [`docker`](https://docs.docker.com/get-docker/) and [`docker-compose`](https://docs.docker.com/compose/) installed on your system.

## Run the application

Kindly follow the next steps in order to be able to run the application: 


1. clone and cd to the root path of this repository.

2. if you're using a windows based system copy the `.env.example` into `.env` manually or using the command :

    ```
    copy .env.example .env
    ```

    OR if you're using a unix/linux based system: 
    
    ```
    cp .env.example .env
    ```

    

3. build and run the docker containers defined at the `docker-compose.yml` file:
    
    ```
    docker-compose build && docker-compose up -d
    ```

4. generate application key 

    ```
    docker-compose exec app php artisan key:generate
    ```
5. migrate database and seed test data 

    ```
    docker-compose exec app php artisan migrate --seed
    ```

6. run tests to make sure everything is okay 

    ```
    docker-compose exec app php artisan test
    ```

    Now the application is available for use on http://localhost:8000

> by default ports 8000, 4306 are not used by any service on your machine, if you run any services on these ports you can change the `DOCKER_APP_PORT` and `DOCKER_DB_PORT` in `.env` file you just copied at root directory of the repository or kindly stop them    

> postman collection for the progect is preset within the `.postman` directory
