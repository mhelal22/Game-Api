
## Introduction
* This is a laravel Game Api ............, 

## Installation


Clone the repository

    git clone https://github.com/mhelal22/Game-Api.git


Generate a new application key

    php artisan key:generate
    
   DEFAULT_USER_TIMEZONE=EET
   

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Run the database seeders

    php artisan db:seed

For linking storage folder in public

    php artisan storage:link

Start the local development server

    php artisan serve


You can now access the server at http://localhost


## Other Important Commands
- To fix php coding standard issues run - composer format
- To clear all cache run - composer clear-all
- To built Cache run - composer cache-all
- To clear and built cache run - composer cc

## APIs

- api/challenge/create  => to create new challenge
- api/challenge/getAll  => to get all challenges
- api/challenge/{id}  => to one challenge
- api/challenge/{id}/submitVideo  => to submit new video
- api/challenge/video/{video_id}/rate  => to vote 


## License


