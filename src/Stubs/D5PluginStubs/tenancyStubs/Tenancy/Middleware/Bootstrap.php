<?php

namespace App\Tenancy\Middleware;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        Grid::registerColumnDisplayer();

        if (file_exists($bootstrap = app_path('Tenancy/bootstrap.php'))) {
            require $bootstrap;
        }

        if (!empty(Admin::$booting)) {
            foreach (Admin::$booting as $callable) {
                call_user_func($callable);
            }
        }

        $this->injectFormAssets();

        if (!empty(Admin::$booted)) {
            foreach (Admin::$booted as $callable) {
                call_user_func($callable);
            }
        }

        return $next($request);
    }

    /**
     * Inject assets of all form fields.
     */
    protected function injectFormAssets()
    {
        $assets = Form::collectFieldAssets();

        Admin::css($assets['css']);
        Admin::js($assets['js']);
    }
}
