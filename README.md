<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://carefer.co/wp-content/uploads/2022/08/logo.png" width="400" alt="Laravel Logo"></a></p>


# About Carefer Backend Challenge

This is the working repository for the backend challenge for a Senior Backend Enginner position at Carefer.


# Dependencies

This project is set up to be built with docker instead of installing any software you don't need on your machine, you should have [`docker`](https://docs.docker.com/get-docker/) and [`docker-compose`](https://docs.docker.com/compose/) installed on your system.

# Run the application

Kindly follow the next steps in order to be able to run the application: 


1. clone and cd to the root path of this repository.
    ```
    cd carefer-be-challenge
    ```

2. if you're using a windows based system copy the `.env.example` into `.env` manually or using the command :

    ```
    copy .env.example .env
    ```

    OR if you're using a unix/linux based system: 
    
    ```
    cp .env.example .env
    ```

    (optional) replace the credentials or ports if you need to; in case deploying to a production environment or leave as is in case of development environment 

    by default ports 8000, 4306 are not used by any service on your machine, if you run any services through these ports you can change the `DOCKER_APP_PORT` and `DOCKER_DB_PORT`
    

3. build and run the docker containers defined at the `docker-compose.yml` file:
    
    ```
    docker-compose up -d --build
    ```
    Now the application is available for use on http://localhost:8000 or the port you chose


<!-- > postman collection for the progect is present within the `.postman` directory     -->

# Database Migration and Seeders
By default the database migrations are run along with the container build, so now we need only to seed the database through the following command:

```
docker-compose exec app db:seed
```

# Testing the application


To run the tests for the application to make sure everything is okay you need to rin the followng command: 

```
docker-compose exec app php artisan test
```

# Comments

- For the business case to be content, I've added the `Slot` model wich holds a `time` and a `day_of_week` assuming the business logic for this is that we have recurring trips at the same time on the same day of the week every week.

    Now through this logic the slot model is correlated to a bus and a trip instances, so we lock the session on the slot instance; meaning a user can request a session on a certain slot, so other users can request sessions on different time slots accordingly.

- I have created a simple authorization flow as the orders crud operations should not be open to public so we have users with roles `customer` and `admin` and both are authenticated with a `jwt` token.





