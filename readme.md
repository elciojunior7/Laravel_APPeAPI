<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

This is a project Laravel tested in Ubuntu/Nginx and Xampp environments.
Frontend in Blade Template and Backend Laravel.
Beside, it implements a REST API with some routes in order to link to some frontend application.

#### It is a little Library application. The goal is to control the loan of books.

## How to execute this project Laravel
### Xampp

- [FIRST, install XAMPP](https://www.apachefriends.org/pt_br/download.html).
- [THEN, install composer](https://getcomposer.org/).

During the composer installation you will be asked about the path. If you install XAMPP first, the Composer will get the XAMPP path by default. This is exactly what you need.

Tested on 7.3.15 Xampp Version and 1.10.1 Composer Version

### Ubuntu 16.04

> sudo apt-get install –y nginx snmp

> sudo apt-get install -y php php-cgi php-cli 
php-common php-curl php-dev php-gd php-json php-mysql php-opcache php-readline php-snmp php-sqlite3 php-xml php-xmlrpc php-bcmath php-bz2 php-fpm php-mbstring php-mcrypt php-phpdbg php-xsl php-zip php-dba

> sudo apt-get install mysql-sever-5.7

> sudo apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev

> php -r "copy('https://getcomposer.org/installer','composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

#### edit project main folder executing in browser
> sudo apt-get install nano
sudo nano /etc/nginx/sites-enabled/default

## How to create a project (It's not necessary doing this step. The project is already done, just clone it)

> laravel new [nome_projeto]

OR
> composer create-project --prefer-dist laravel/laravel [nome_projeto]

## How to execute laravel project

Windows (accessing Xampp htdocs folder)
Ubuntu (accessing /var/www/html)

...Then...  
> cd [nome_projeto]
composer install (to install dependencies. "php artisan" doesn't work without this step)

Renaming .env.example in project folder to .env  
> php artisan key:generate (this step create an APP_KEY inside .env file. It's important)

Edit the following lines in .env file:  
> DB_CONNECTION=(your DB)  
DB_PORT=3306(your DB port)  
DB_DATABASE=(your DB schema)  
DB_USERNAME=(your DB username)  
DB_PASSWORD=(your DB password)  
AUTH_API_TOKEN=(a password to access the API)

There is a configuration example in .env.txt file. The example uses MySql as DB.  
DON'T change the APP_KEY line that you just created in .env.

#### Windows
> php artisan serve (after this step the laravel app is available)

#### Ubuntu
> sudo /etc/init.d/nginx restart

## Database

It's necessary to create a schema in DB according to DB configurations in .env.
Then, let's create tables in DB according to migrates classes declared in project:
> php artisan migrate (to rollback the last creation/migrate, execute php artisan migrate:rollback)
