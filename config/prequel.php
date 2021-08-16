<?php
$relationships = getenv("PLATFORM_RELATIONSHIPS");
$database = false;
$redis = false;
if ($relationships) {
    $relationships = json_decode(base64_decode($relationships), true);
    foreach ($relationships['database'] as $endpoint) {
        if (empty($endpoint['query']['is_master'])) {
            continue;
        }
        $database = $endpoint;
    }
    if (array_key_exists('redis', $relationships)) {
        $redis = $relationships['redis'][0];
    }
}
return [


    /*
        |--------------------------------------------------------------------------
        | Prequel Master Switch : boolean
        |--------------------------------------------------------------------------
        |
        | Manually disable/enable Prequel, if in production Prequel will always be
        | disabled. Reason being that nobody should ever be able to directly look
        | inside your database besides you or your dev team (obviously).
        |
        */

    // 'enabled' => env('PREQUEL_ENABLED', true),
    'enabled' => env('PREQUEL_ENABLED', ($variables && array_key_exists('PREQUEL_ENABLED', $variables)) ? $variables['PREQUEL_ENABLED'] : 'null'),


    /*
        |--------------------------------------------------------------------------
        | Prequel Locale : string
        |--------------------------------------------------------------------------
        |
        | Choose what language Prequel should display in.
        |
        */

    // 'locale' => env('APP_LOCALE', 'en'),
    'locale' => env('APP_LOCALE', ($variables && array_key_exists('APP_LOCALE', $variables)) ? $variables['APP_LOCALE'] : 'null'),


    /*
        |--------------------------------------------------------------------------
        | Prequel Path
        |--------------------------------------------------------------------------
        |
        | The path where Prequel will be residing. Note that this does not affect
        | Prequel API routes.
        |
        */

    'path' => 'prequel',


    /*
        |--------------------------------------------------------------------------
        | Laravel asset generation suffix and namespace definition
        |--------------------------------------------------------------------------
        |
        | Here you can define your preferred asset suffixes and directory/namespaces.
        | Separate with a double backwards slash to define namespace and directory
        | location. Everything after the last '\\' will be treated as a suffix.
        | Note that the backslash needs to be escaped with an extra backslash
        |
        | For example
        |
        |  Configuration
        |     'suffixes' => [
        |           'model'  => 'Models\\Model',
        |           'seeder' => 'MyMadeUpSeederSuffix'
        |       ]
        |
        |  When generating for `users` table
        |     (directory) app/models/UserModel.php
        |     (qualified class) App\Models\UserModel
        |     (directory) database/seeds/UserMyMadeUpSeederSuffix.php
        |
        */

    'suffixes' => [
        'model'      => 'Models\\',
        'seeder'     => 'Seeder',
        'factory'    => 'Factory',
        'controller' => 'Controller',
        'resource'   => 'Resource',
    ],


    /*
        |--------------------------------------------------------------------------
        | Prequel Database Configuration : array
        |--------------------------------------------------------------------------
        |
        | This enables you to fffully configure your database connection for Prequel.
        |
        */

    'database' => [
        'connection' => env('DB_CONNECTION', ($database) ? $database['connection'] : 'localhost'),
        'host'      => env('DB_HOST', ($database) ? $database['host'] : 'localhost'),
        'port'      => env('DB_PORT', ($database) ? $database['port'] : '3306'),
        'database'  => env('DB_DATABASE', ($database) ? $database['path'] : 'forge'),
        'username'  => env('DB_USERNAME', ($database) ? $database['username'] : 'forge'),
        'password'  => env('DB_PASSWORD', ($database) ? $database['password'] : 'forge'),
    ],


    /*
        |--------------------------------------------------------------------------
        | Prequel ignored databases and tables : array
        |--------------------------------------------------------------------------
        | Databases and tables that will be ignored during database discovery.
        |
        | Using 'mysql' => ['foo']  ignores only the mysql.foo table.
        | Using 'mysql' => ['*'] ignores the entire mysql database.
        |
        */

    'ignored' => [
        '#mysql50#lost+found' => ['*'],

        // -- Frequently ignored tables --
        // 'information_schema'  => ['*'],
        // 'sys'                 => ['*'],
        // 'performance_schema'  => ['*'],
        // 'mysql'               => ['*'],
    ],


    /*
        |--------------------------------------------------------------------------
        | Prequel pagination per page : integer
        |--------------------------------------------------------------------------
        |
        | When Prequel retrieves paginated information, this is the number of
        | records that will be in each page.
        |
        */

    'pagination' => 100,


    /*
        |--------------------------------------------------------------------------
        | Prequel middleware : array
        |--------------------------------------------------------------------------
        |
        | Define custom middleware for Prequel to use.
        |
        | Ex. 'web', Protoqol\Prequel\Http\Middleware\Authorised::class
        |
        */

    'middleware' => [
        // Protoqol\Prequel\Http\Middleware\Authorised::class,
    ],
];
