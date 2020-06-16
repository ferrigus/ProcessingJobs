## ProcessingJobs Project

This repository is about a small process of list of jobs that can be managed by submitters and processors. This project was created with Laravel 7, please see their documentation first and then follow the next steps:

- Clone repository and run composer install.
- Create an empty database and edit the .env file with database credentials. Also set QUEUE_CONNECTION=database.
- Run command php artisan migrate to create all tables
- Run command php artisan db:seed --force to create fake data.
