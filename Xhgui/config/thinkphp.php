<?php
/**
 * @author: Gilbert.He <guangzhonghe@hk01.com>
 * Date: 2018/10/29
 * Time: 11:59
 * FILENAME:thinkphp.php
 */

return [
    'debug'           => \think\Env::get('APP_DEBUG', false),
    'mode'            => \think\Env::get('APP_NAME', 'test'),
    'filter_var'      => empty(\think\Env::get('XHGUI_FILTER_VAR', '')) ?
        [] : explode(',', \think\Env::get('XHGUI_FILTER_VAR')),
    'save.handler'    => 'mongodb',
    'db.host'         => sprintf('mongodb://%s', \think\Env::get('XHGUI_MONGO_URI')),
    'db.db'           => \think\Env::get('XHGUI_MONGO_DB', 'xhprof'),
    // Allows you to pass additional options like replicaSet to MongoClient.
    // 'username', 'password' and 'db' (where the user is added)
    'db.options'      => ['ssl' => in_array(\think\Env::get('APP_ENV'), ['stg', 'prod']) ? true : false],
    'templates.path'  => dirname(__DIR__) . '/src/templates',
    'date.format'     => 'M jS H:i:s',
    'detail.count'    => 6,
    'page.limit'      => 25,
    'profiler.enable' => (int)\think\Env::get('XHGUI_PROFILING_RATIO', 0),
    'profiler.options' => [],
    'profiler.second' => \think\Env::get('XHGUI_EXECUTE_SECOND', 0),
];
