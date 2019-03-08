<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => database_path('database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
           'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'database'),
            'username'  => env('DB_USERNAME', 'username'),
            'password'  => env('DB_PASSWORD', 'password'),
			//'port'     => env('DB_PORT_ZCOM',3306), 
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
			'engine'    => null,
            'options'   => array(
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            )
        ],
		'mysqlzcom' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST_ZCOM_z', 'localhost'),
            'database'  => env('DB_DATABASE_ZCOM_z', 'database'),
            'username'  => env('DB_USERNAME_ZCOM_z', 'username'),
            'password'  => env('DB_PASSWORD_ZCOM_z', 'password'),
			//'port'     => env('DB_PORT_ZCOM',3306), 
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
			'engine'    => null,
        ], 
		'tracker' => [
			'driver'   => 'mysql',
			'host'      => env('DB_HOST_ZCOM', 'localhost'),
            'database'  => env('DB_DATABASE_ZCOM', 'database'),
            'username'  => env('DB_USERNAME_ZCOM', 'username'),
            'password'  => env('DB_PASSWORD_ZCOM', 'password'), 
			'collation' => 'utf8mb4_unicode_ci',
			'strict' => false,    // to avoid problems on some MySQL installs
		],
		'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('DB_HOST', 'localhost'),
			'port'     => env('DB_PORT', 27017),
            'database' => env('MONGO_DB', 'database')
        ], 
		'mongodbcc' => [
            'driver'   => 'mongodb',
            'host'     => env('DB_HOST_MONGO_CC', 'ip'),
			'port'     => env('DB_PORT_MONGO_CC', 27017),
            'database' => env('MONGO_DB_CC', 'database')
        ],
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port'     => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
