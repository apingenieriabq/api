<?php

require '../config.php';

require DIR_API.'vendor/autoload.php';

require DIR_API.'libs/autoload.php';
require DIR_API.'libs/Motor.php';

// Using Medoo namespace
use Medoo\Medoo;

$BD_AP_PRINCIPAL = new Medoo([
    // required
    'database_type' => 'mysql',
    'database_name' => 'apingeni_flat',
    'server' => '159.203.126.221',
    'username' => 'root',
    'password' => 'Web2019*',

    // [optional]
    'charset' => 'utf8',
    'collation' => 'utf8_spanish2_ci',
    'port' => 3306,

    // [optional] Table prefix
    //prefix' => 'PREFIX_',

    // [optional] Enable logging (Logging is disabled by default for better performance)
    'logging' => true,

    // [optional] MySQL socket (shouldn't be used with server and port)
    //'socket' => '/tmp/mysql.sock',

    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],

    // [optional] Medoo will execute those commands after connected to the database for initialization
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES',
    ],
]);
$BD_AP_LOGS = new Medoo([
    // required
    'database_type' => 'mysql',
    'database_name' => 'apingeni_logs',
    'server' => '159.203.126.221',
    'username' => 'root',
    'password' => 'Web2019*',

    // [optional]
    'charset' => 'utf8',
    'collation' => 'utf8_spanish2_ci',
    'port' => 3306,

    // [optional] Table prefix
    //prefix' => 'PREFIX_',

    // [optional] Enable logging (Logging is disabled by default for better performance)
    'logging' => true,

    // [optional] MySQL socket (shouldn't be used with server and port)
    //'socket' => '/tmp/mysql.sock',

    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],

    // [optional] Medoo will execute those commands after connected to the database for initialization
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES',
    ],
]);
