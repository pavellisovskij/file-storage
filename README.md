<h1 align="center">File Storage</h1>

## Server Requirements

- PHP 8.1
- MySQL 8

## Project Deployment

1. Install the project dependencies: `composer install`
2. Install the frontend dependencies: `npm install`
3. Configure the project by **.env**
4. Change email addresses for user and admin in _**database/seeders/UserSeeder.php**_
5. Run migrations and seed users: `php artisan migrate --seed`
6. Create the symbolic link: `php artisan storage:link`
7. Compile CSS and JavaScript: `npm run prod`
