# XHGUI Collector - laravel5/thinkphp5
fork from [xhgui-collector](https://github.com/perftools/xhgui-collector)

改造后支持 psr-4，框架可以一键对日志进行采集。

数据采集存储于mongodb,其它存储方式暂时没做测试。

展示可以使用：
* [xhgui](https://github.com/perftools/xhgui)
* [xhgui-branch](https://github.com/laynefyc/xhgui-branch)

## 使用指南
* `composer require guangzhonghedd01/xhgui-collector` 
* env 配置信息
```
XHGUI_MONGO_URI=127.0.0.1:27017
XHGUI_MONGO_DB=xhprof
XHGUI_PROFILING_RATIO=100
XHGUI_EXECUTE_SECOND=1
XHGUI_PROFILING=enabled
XHGUI_FILTER_VAR=XHGUI_FILTER_VAR
APP_NAME=test
```

| env | description | example | default |
| ---- | ----------- | ------- | ------- |
| XHGUI_MONGO_URI | mongodb 地址 | `XHGUI_MONGO_URI=mongo:27017` | 127.0.0.1:27017 |
| XHGUI_MONGO_DB | 库名 | `XHGUI_MONGO_DB=xhprof` | xhprof |
| XHGUI_PROFILING_RATIO | 采样比率 | `XHGUI_PROFILING_RATIO=50` 对50%请求进行采集 | `XHGUI_PROFILING_RATIO=100` |
| XHGUI_PROFILING | 采集开关 | `XHGUI_PROFILING=enabled` | 如果不填写值就是关闭状态 |
| XHGUI_EXECUTE_SECOND | 执行时间门槛(秒) | `XHGUI_EXECUTE_SECOND=1` | XHGUI_EXECUTE_SECOND=1 执行时间大于1秒才采集 |
| XHGUI_FILTER_VAR | 需要过滤ENV的敏感数据信息 | `XHGUI_PROFILING=xx_password` | 没有默认值 |
| APP_NAME | 项目名称 | `APP_NAME=test` | test |

### Laravel
* 将Xhgui/config/laravel.php 拷贝到 laravel 项目config文件夹下，更名为xhgui.php
* 在需要采集的路由上增加中间件
```
class Xhprof extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        xhgui_laravel();

        return $next($request);
    }
}
``` 

### Thinkphp
* 将Xhgui/config/laravel.php 拷贝到项目application/extra/件夹下，更名为xhgui.php(extrea文件夹可能不存在)
* 增加/www/web/ydapi-dadi01-com/extend/hook目录下新增Xhgui.php,
```
<?php
namespace hook;

class Xhgui
{
    public function run(&$params)
    {
        \Guangzhong\Xhgui\xhgui_thinkphp();
    }
}
```
* 在application/tags.php 增加行为注入
```
<?php

// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => [

    ],
    // 应用开始
    'app_begin'    => [],
    // 模块初始化
    'module_init'  => ['hook\\Xhgui'],
    // 操作开始执行
    'action_begin' => [''],
    // 视图内容过滤
    'view_filter'  => [''],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
];
```
## 扩展支持
* php >= 7.0.0
* mongodo >= 1.5.0
* tideways 4.1.6 (官方最新版本为5.0，暂不支持)

![tideways](https://github.com/guangzhonghedd01/xhgui-collector/blob/master/Xhgui/images/extension_tideways.jpg)

![tideways](https://github.com/guangzhonghedd01/xhgui-collector/blob/master/Xhgui/images/extension_mongodb.jpg)


