<?php

namespace App\Tenancy\Facades;


/**
 * Class Admin.
 *
 * @method static \Encore\Admin\Grid grid($model, \Closure $callable)
 * @method static \Encore\Admin\Form form($model, \Closure $callable)
 * @method static \Encore\Admin\Show show($model, $callable = null)
 * @method static \Encore\Admin\Tree tree($model, \Closure $callable = null)
 * @method static \Encore\Admin\Layout\Content content(\Closure $callable = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void css($css = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void js($js = null)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void script($script = '')
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null user()
 * @method static string title()
 * @method static void navbar(\Closure $builder = null)
 * @method static void registerAuthRoutes()
 * @method static void extend($name, $class)
 * @method static void disablePjax()
 */
class Tenancy extends \Illuminate\Support\Facades\Facade{
    protected static function getFacadeAccessor()
    {
        return \App\Tenancy\Tenancy::class;
    }

}
