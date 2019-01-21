<?php
/**
 * Default configuration for Xhgui
 */

return [
    'debug'           => env('APP_DEBUG', false),
    'mode'            => env('APP_NAME', 'test'),
    'filter_var'      => empty(env('XHGUI_FILTER_VAR', '')) ?
        [] : explode(',', env('XHGUI_FILTER_VAR')),
    'save.handler'    => 'mongodb',
    'db.host'         => sprintf('mongodb://%s', env('XHGUI_MONGO_URI', '127.0.0.1:27017')),
    'db.db'           => env('XHGUI_MONGO_DB', 'xhprof'),
    // Allows you to pass additional options like replicaSet to MongoClient.
    // 'username', 'password' and 'db' (where the user is added)
    'db.options'      => ['ssl' => in_array(env('APP_ENV'), ['stg', 'prod']) ? true : false],
    'templates.path'  => dirname(__DIR__) . '/src/templates',
    'date.format'     => 'M jS H:i:s',
    'detail.count'    => 6,
    'page.limit'      => 25,
    'profiler.enable' => (int)env('XHGUI_PROFILING_RATIO', 0),
    'profiler.options' => [],
    'profiler.second' => env('XHGUI_EXECUTE_SECOND', 0),
];
