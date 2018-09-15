# XHGUI Collector - larval/thinkphp
fork from [xhgui-collector](https://github.com/perftools/xhgui-collector)

改造后支持 psr-4，框架可以一键对日志进行采集。

数据采集存储于mongodb,其它存储方式暂时没做测试。

展示可以使用：
* [xhgui](https://github.com/perftools/xhgui)
* [xhgui-branch](https://github.com/laynefyc/xhgui-branch)

## 使用指南
* `composer require guangzhonghedd01/xhgui-collector` 
* Middleware在需要采集日志的入口增加。
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
* env 配置信息
```
| env | description | example | default |
| ---- | ----------- | ------- | ------- |
| `XHGUI_MONGO_URI` | mongodb 地址 | `XHGUI_MONGO_URI=mongo:27017` | 127.0.0.1:27017 |
| `XHGUI_MONGO_DB` | 库名 | `XHGUI_MONGO_DB=xhprof` | xhprof |
| `XHGUI_PROFILING_RATIO` | 采样比率 | `XHGUI_PROFILING_RATIO=50` 对50%请求进行采集 | `XHGUI_PROFILING_RATIO=100` |
| `XHGUI_PROFILING` | 采集开关 | `XHGUI_PROFILING=enabled` | 如果不填写值就是关闭状态 |
| `XHGUI_FILTER_VAR` | 需要过滤ENV的敏感数据信息 | `XHGUI_PROFILING=xx_password` | 没有默认值 |
```

## 扩展支持
* php >= 7.0.0
* mongodo >= 1.5.0
* tideways 4.1.6 (官方最新版本为5.0，暂不支持)

![tideways](https://github.com/guangzhonghedd01/xhgui-collector/Xhgui/images/extension_tideways.jpg)

![tideways](https://github.com/guangzhonghedd01/xhgui-collector/Xhgui/images/extension_mongodb.jpg)


