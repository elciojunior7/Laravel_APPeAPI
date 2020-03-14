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

Tested on 7.3.15 Xampp Version and 1.9.3 Composer Version

### Ubuntu 16,04

sudo apt-get install –y nginx snmp

sudo apt-get install -y php php-cgi php-cli 
php-common php-curl php-dev php-gd php-json php-mysql php-opcache php-readline php-snmp php-sqlite3 php-xml php-xmlrpc php-bcmath php-bz2 php-fpm php-mbstring php-mcrypt php-phpdbg php-xsl php-zip php-dba

sudo apt-get install mysql-sever-5.7

sudo apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev

php -r "copy('https://getcomposer.org/installer','composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

#### edit project main folder executing in browser
sudo apt-get install nano
sudo nano /etc/nginx/sites-enabled/default

## How to create a project (In this case is not necessary do this, the project is already done. Just clone it)

laravel new [nome_projeto]
OU
composer create-project --prefer-dist laravel/laravel [nome_projeto]

## How to execute laravel project
Windows (accessing Xampp htdocs folder)
Ubuntu (accessing /var/www/html)

...Then...

cd [nome_projeto]

php artisan serve
