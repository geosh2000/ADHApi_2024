<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'cycoasis_apirest_admin',
        'password'     => '@8370Apirest',
        'database'     => 'cycoasis_apirest',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
    ];
   
    public array $mysql = [
        'DSN'          => '',
        'hostname'     => '127.0.0.1',
        'username'     => 'root',
        'password'     => 'root',
        'database'     => 'adh_api',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 8889,
        'numberNative' => false,
    ];
    
    public array $adh_wh = [
        'DSN'          => '',
        'hostname'      => 'tcp:atelier-datawarehouse.database.windows.net', // Aquí se especifica el protocolo TCP para Azure SQL Database
        'username'     => 'CVReports3',
        'password'     => '7ZUMPHoLR7$8',
        'database'     => 'DataWareHouse',
        'DBDriver'     => 'SQLSRV',
        'DBPrefix'     => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => true, // Se recomienda utilizar el cifrado SSL para conexiones seguras a Azure SQL Database
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
    ];
    
    public array $adh_crs = [
        'DSN'          => '',
        'hostname'      => 'tcp:atelier-hoteles.database.windows.net', // Aquí se especifica el protocolo TCP para Azure SQL Database
        'username'     => 'CVReports3',
        'password'     => '7ZUMPHoLR7$8',
        'database'     => 'Atelier_Front_CRS',
        'DBDriver'     => 'SQLSRV',
        'DBPrefix'     => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => true, // Se recomienda utilizar el cifrado SSL para conexiones seguras a Azure SQL Database
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
    ];

    public array $new_adh_crs = [
        'DSN'          => '',
        'hostname'      => 'tcp:atelier-hoteles.database.windows.net', // Aquí se especifica el protocolo TCP para Azure SQL Database
        'username'     => 'CVReports3',
        'password'     => '7ZUMPHoLR7$8',
        'database'     => 'Atelier_Reservations',
        'DBDriver'     => 'SQLSRV',
        'DBPrefix'     => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => true, // Se recomienda utilizar el cifrado SSL para conexiones seguras a Azure SQL Database
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
    ];

    public array $production = [
        'DSN'         => '',
        'hostname'    => '65.99.248.168',
        'username'     => 'cycoasis_apirest_admin',
        'password'     => '@8370Apirest',
        'database'    => 'cycoasis_adh',
        'DBDriver'    => 'MySQLi',
        'DBPrefix'    => '',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => 'utf8_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
    ];

    public $validDrivers = [
        'mysqli' => 'App\Libraries\Database\MyDBMysqliDriver',
        // Add other valid drivers here if needed
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'development') {
            $this->defaultGroup = 'default';
        }
    }
}
