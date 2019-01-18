<?php
/**
 * @author: Gilbert He <guangzhonghe@hk01.com>
 * Date: 2018/9/14
 * Time: 2:29 PM
 * FILENAME:Import.php
 *
 * @example
 * Guangzhong\Xhgui\Import::laravel();
 */

namespace Guangzhong\Xhgui;

class Import
{
    public function __construct()
    {
        if (!\extension_loaded('xhprof')
            && !\extension_loaded('uprofiler')
            && !\extension_loaded('tideways')
            && !\extension_loaded('tideways_xhprof')
        ) {
            error_log('xhgui - either extension xhprof, uprofiler, tideways or tideways_xhprof must be loaded');

            return;
        }
    }

    /**
     * laravel 接入
     */
    public static function laravel()
    {
        Config::load(config('xhgui'));
        if ((!\extension_loaded('mongo')
             && !\extension_loaded('mongodb'))
            && Config::read('save.handler') === 'mongodb') {
            error_log('xhgui - extension mongo not loaded');

            return;
        }

        if (!Config::shouldRun()) {
            return;
        }

        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        }
        $options = Config::read('profiler.options');
        if (\extension_loaded('uprofiler')) {
            uprofiler_enable(UPROFILER_FLAGS_CPU | UPROFILER_FLAGS_MEMORY, $options);
        } else if (\extension_loaded('tideways')) {
            tideways_enable(TIDEWAYS_FLAGS_CPU | TIDEWAYS_FLAGS_MEMORY | TIDEWAYS_FLAGS_NO_SPANS, $options);
        } elseif (\extension_loaded('tideways_xhprof')) {
            tideways_xhprof_enable(TIDEWAYS_XHPROF_FLAGS_CPU | TIDEWAYS_XHPROF_FLAGS_MEMORY);
        } else {
            if (PHP_MAJOR_VERSION === 5 && PHP_MINOR_VERSION > 4) {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS, $options);
            } else {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY, $options);
            }
        }

        register_shutdown_function(
            function () {
                if (\extension_loaded('uprofiler')) {
                    $data['profile'] = uprofiler_disable();
                } else if (\extension_loaded('tideways_xhprof')) {
                    $data['profile'] = tideways_xhprof_disable();
                } else if (\extension_loaded('tideways')) {
                    $data['profile'] = tideways_disable();
                    $sqlData = tideways_get_spans();
                    $data['sql'] = [];
                    if (isset($sqlData[1])) {
                        foreach ($sqlData as $val) {
                            if (isset($val['n']) && $val['n'] === 'sql' && isset($val['a']) && isset($val['a']['sql'])) {
                                $_time_tmp = (isset($val['b'][0]) && isset($val['e'][0])) ? ($val['e'][0] - $val['b'][0]) : 0;
                                if (!empty($val['a']['sql'])) {
                                    $data['sql'][] = [
                                        'time' => $_time_tmp,
                                        'sql'  => $val['a']['sql'],
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    $data['profile'] = xhprof_disable();
                }

                if (!empty($data['profile'])){
                    $profile = [];
                    foreach($data['profile'] as $key => $value) {
                        $profile[strtr($key, ['.' => '_'])] = $value;
                    }

                    $data['profile'] = $profile;
                }

                // ignore_user_abort(true) allows your PHP script to continue executing, even if the user has terminated their request.
                // Further Reading: http://blog.preinheimer.com/index.php?/archives/248-When-does-a-user-abort.html
                // flush() asks PHP to send any data remaining in the output buffers. This is normally done when the script completes, but
                // since we're delaying that a bit by dealing with the xhprof stuff, we'll do it now to avoid making the user wait.
                ignore_user_abort(true);
                flush();

                $uri = array_key_exists('REQUEST_URI', $_SERVER)
                    ? $_SERVER['REQUEST_URI']
                    : null;
                if (empty($uri) && isset($_SERVER['argv'])) {
                    $cmd = basename($_SERVER['argv'][0]);
                    $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
                }

                $time = array_key_exists('REQUEST_TIME', $_SERVER)
                    ? $_SERVER['REQUEST_TIME']
                    : time();

                // In some cases there is comma instead of dot
                $delimiter = (strpos($_SERVER['REQUEST_TIME_FLOAT'], ',') !== false) ? ',' : '.';
                $requestTimeFloat = explode($delimiter, $_SERVER['REQUEST_TIME_FLOAT']);
                if (!isset($requestTimeFloat[1])) {
                    $requestTimeFloat[1] = 0;
                }

                $requestTs = ['sec' => $time, 'usec' => 0];
                $requestTsMicro = ['sec' => $requestTimeFloat[0], 'usec' => $requestTimeFloat[1]];

                //过滤敏感数据信息，比如密码之类的
                $filterVar = Config::read('filter_var');
                foreach ($filterVar as $v) {
                    if (isset($_SERVER[$v])) {
                        unset($_SERVER[$v]);
                    }
                }

                $data['meta'] = [
                    'url'              => $uri,
                    'SERVER'           => $_SERVER,
                    'get'              => $_GET,
                    'env'              => '', //去掉env信息
                    'simple_url'       => Util::simpleUrl($uri),
                    'request_ts'       => $requestTs,
                    'request_ts_micro' => $requestTsMicro,
                    'request_date'     => date('Y-m-d', $time),
                ];
                if (isset($requestTsMicro['usec'])) {
                    //执行时间转换成秒
                    $sec = $requestTsMicro['usec'] / 1000000;
                    $set_time = Config::read('profiler.second');
                    if ($sec < $set_time) {
                        return;
                    }
                }
                $data['project'] = Config::read('mode');

                try {
                    $config = Config::all();
                    $config += ['db.options' => []];

                    $saver = Saver::factory($config);
                    $saver->save($data);

                } catch (\Exception $e) {
                    error_log('xhgui - ' . $e->getMessage());
                }
            }
        );
    }
}
