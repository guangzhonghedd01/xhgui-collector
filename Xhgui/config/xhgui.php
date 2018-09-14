<?php
/**
 * Default configuration for Xhgui
 */

return [
    'debug'           => env('APP_DEBUG', false),
    'mode'            => env('APP_NAME', 'eatojoy'),
    'filter_var'      => empty(env('XHGUI_FILTER_VAR', '')) ?
        [] : explode(',', env('XHGUI_FILTER_VAR')),
    'save.handler'    => 'mongodb',
    'db.host'         => sprintf('mongodb://%s', env('XHGUI_MONGO_URI', '127.0.0.1:27017')),
    'db.db'           => env('XHGUI_MONGO_DB', 'xhprof'),

    // Allows you to pass additional options like replicaSet to MongoClient.
    // 'username', 'password' and 'db' (where the user is added)
    'db.options'      => [],
    'templates.path'  => dirname(__DIR__) . '/src/templates',
    'date.format'     => 'M jS H:i:s',
    'detail.count'    => 6,
    'page.limit'      => 25,

    // Profile x in 100 requests. (E.g. set XHGUI_PROFLING_RATIO=50 to profile 50% of requests)
    // You can return true to profile every request.
    'profiler.enable' => function () {
        if (env('XHGUI_PROFILING', '') == '') {
            return false;
        }

        $ratio = env('XHGUI_PROFILING_RATIO', 100);

        return mt_rand(1, 100) <= $ratio;
    },

    'profiler.simple_url' => function ($url) {
        return preg_replace('/\=\d+/', '', $url);
    },

    'profiler.options' => [],
];
