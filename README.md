

## How To Install Project
## Server Requirements
The Laravel framework has a few system requirements. You should ensure that your web server has the following minimum PHP version and extensions:

- PHP >= 8.2
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension


## how to deploy Project on server

- Login and Go To Your Server Cpanel/FTP/SFTP
- Upload Project Zip File And Unzip In Your Project Directory.
- Create Database.
- Open .ENV File And Update Database Information.
- Open PHPMyAdmin.
##
- Select Created Database From List.
    - - Database Migrations (Use SSH/terminal)
    - - - php artisan migrate --force
##
- Open .ENV File and Update Like This Below
- - APP_NAME=YOUR PROJECT NAME
- - APP_ENV=production
- - APP_DEBUG=false
- - APP_URL=YOUR DOMAIN
##
- Storage Link (If You Need To Use) (Use SSH/terminal)
- - php artisan storage:link
##
- Cache Clear (Use SSH/terminal)
- - php artisan config:cache
- - php artisan route:cache
- - php artisan view:cache
- - php artisan optimize:clear

- go to public directory and delete hot file in production mode
- [Visit For More Information](https://laravel.com/docs/11.x/deployment)
