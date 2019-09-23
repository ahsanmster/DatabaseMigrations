# Database Migrations
Database migration is a package for laravel applications.This package read mysql database which mention in .env file of laravel application and this package make migration files against database.By-default this package check db connection name,db name,user name and  password which mention in .env file and we can also change db connection name,db user name and password on run time and this package check given credentials and make migration against given credentials.In this package user delete previous migrations files on run time.After making migration files user  migrate migrations in any database. 

## Prerequisites
To install dependency
```
composer require doctrine/dbal
```

## Installation
You can install the package via composer.
```
composer require digitalwaves/database-migration
```
This command register package in vendor folder.

## Configuration
After download package add service provider in config/app.php Package Service Providers list
```
digitalWaves\createMigrations\MigrationServiceProvider::class,
```
After add service provider enter this command
```
composer dump-autoload
```
This command register package with laravel application and we can use easily package command.

## usage
Run this artisan command to make migrations files
```
php artisan make:migration-files
```
after run this command display message on CLI
```
 Do you want to change DB_HOST ? (yes/no) [no]:
```
if user want to change db host then press yes or y and enter db host otherwise press no or n to proceed further and next message display on CLI
```
 Do you want to change DB_DATABASE ? (yes/no) [no]:
```
if user want to change db name then press yes or y and enter db name otherwise press no or n to proceed further and next message display on CLI
```
 Do you want to change DB_USERNAME ? (yes/no) [no]:
```
if user want to change db user name then press yes or y and enter db user name otherwise press no or n to proceed further and next message display on CLI
```
 Do you want to change DB_PASSWORD ? (yes/no) [no]:
```
if user want to change db password then press yes or y and enter db password otherwise press no or n to proceed further and next message display on 
```
 Do you want to delete all previous  migrations files ? (yes/no) [no]:
```
if user want to delete all previous migrations files then press yes or y and this package automatically delete all previous migrations files otherwise press no or n to proceed further and show progress bar of no of making migrations files. 
## Authors

* **Digital Waves** - *Initial work* - [DigitalWaves](http://www.digitalwaves.net/)

See also the list of [contributors](https://github.com/ahsanmster/DatabaseMigrations/graphs/contributors) who participated in this project.



