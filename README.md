# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone git@github.com:harshilmatrixhive/messaging-app.git

Switch to the repo folder

    cd messaging-app

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Install frontend dependencies using npm

    npm install && npm run dev

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

## Database seeding

**Populate the database with seed data with relationships which includes users. This can help you to quickly start testing the app.**

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh