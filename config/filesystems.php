<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Default Filesystem Disk
     |--------------------------------------------------------------------------
     |
     | Here you may specify the default filesystem disk that should be used
     | by the framework. The "local" disk, as well as a variety of cloud
     | based disks are available to your application for file storage.
     |
     */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
     |--------------------------------------------------------------------------
     | Filesystem Disks
     |--------------------------------------------------------------------------
     |
     | Below you may configure as many filesystem disks as necessary, and you
     | may even configure multiple disks for the same driver. Examples for
     | most supported storage drivers are configured here for reference.
     |
     | Supported drivers: "local", "ftp", "sftp", "s3"
     |
     */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        /*
         |--------------------------------------------------------------------------
         | Bunny.net Storage (Vía FTP)
         |--------------------------------------------------------------------------
         | Usado para guardar imágenes pesadas y archivos estáticos
         | Datos sacados del panel FTP & API de Bunny
         */
        'bunny_storage' => [
            'driver' => 'ftp',
            'host' => env('BUNNY_HOSTNAME', 'ny.storage.bunnycdn.com'),
            'username' => env('BUNNY_USERNAME', 'gente-piola'),
            'password' => env('BUNNY_PASSWORD', '8b078c5f-ad56-4ad8-a4a7b28e775f-63eb-4d16'),
            'port' => env('BUNNY_PORT', 21),
            'passive' => env('BUNNY_PASSIVE', true),
            'ssl' => env('BUNNY_SSL', true),
            'timeout' => env('BUNNY_TIMEOUT', 30),
            'url' => env('BUNNY_PULL_ZONE_URL', 'https://gentepiola.b-cdn.net'),
        ],

    ],

    /*
     |--------------------------------------------------------------------------
     | Symbolic Links
     |--------------------------------------------------------------------------
     |
     | Here you may configure the symbolic links that will be created when the
     | `storage:link` Artisan command is executed. The array keys should be
     | the locations of the links and the values should be their targets.
     |
     */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
