<?php
namespace App\Tenancy;
use App\Admin\Extensions\Form;
use App\Tenancy\Controllers\AuthController;
use App\Tenancy\Extensions\Grid;
use App\Tenancy\Extensions\Layout\Content;
use Auth;
use Closure;
use Encore\Admin\Admin;
use Encore\Admin\Tree;

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/2/18
 * Time: 15:42
 */
class Tenancy extends Admin {

    /**
     * Returns the long version of Laravel-admin.
     *
     * @return string The long application version
     */
    public static function getLongVersion()
    {
        return sprintf('Laravel-admin <comment>version</comment> <info>%s</info>', self::VERSION);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \Encore\Admin\Grid
     *
     * @deprecated since v1.6.1
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \Encore\Admin\Form
     *
     *  @deprecated since v1.6.1
     */
    public function form($model, Closure $callable)
    {
        return new Form($this->getModel($model), $callable);
    }

    /**
     * Build a tree.
     *
     * @param $model
     *
     * @return \Encore\Admin\Tree
     */
    public function tree($model, Closure $callable = null)
    {
        return new Tree($this->getModel($model), $callable);
    }


    /**
     * @param Closure $callable
     *
     * @return \Encore\Admin\Layout\Content
     *
     * @deprecated since v1.6.1
     */
    public function content(Closure $callable = null)
    {
        return new Content($callable);
    }


    /**
     * Get current login user.
     *
     * @return mixed
     */
    public function user()
    {
        return Auth::guard('tenancy')->user();
    }


    /**
     * Register the auth routes.
     *
     * @return void
     */
    public function registerAuthRoutes()
    {
        $attributes = [
            'prefix'     => config('tenancy.route.prefix'),
            'middleware' => config('tenancy.route.middleware'),
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Routing\Router $router */
            $router->namespace('App\Tenancy\Controllers')->group(function ($router) {


                $authController = "Auth\AuthController";
                /* @var \Illuminate\Routing\Router $router */
                $router->get('auth/login', $authController . '@getLogin')->name("tenancy.login");
                $router->post('auth/login', $authController . '@postLogin')->name("tenancy.login_submit");
                $router->get('auth/logout', $authController . '@getLogout')->name("tenancy.logout");
                $router->get('auth/setting', $authController . '@getSetting');
                $router->put('auth/setting', $authController . '@putSetting');


                $router->get('auth/success','Auth\VerificationController@success')->name('tenancy.success');
   $router->get('auth/email/verify', 'Auth\VerificationController@show')->name('tenancy.verification.notice');
                $router->get('auth/email/verify/{id}', 'Auth\VerificationController@verify')->name('tenancy.verification.verify');
                $router->get('auth/email/resend', 'Auth\VerificationController@resend')->name('tenancy.verification.resend');

            });

        });
            $extra_auth_attr = [
                'prefix'     => config('tenancy.route.prefix'),
                'middleware' => "web",
                'namespace' => 'App\\Tenancy\\Controllers',
            ];

            //额外添加注册路由
            app('router')->group($extra_auth_attr, function (\Illuminate\Routing\Router $router) {
                $router->get('auth/register', 'Auth\RegisterController@showRegistrationForm')->name('tenancy.register');
                $router->post('auth/register', 'Auth\RegisterController@register');

                $router->get('auth/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('tenancy.password.request');
                $router->post('auth/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('tenancy.password.email');
                $router->get('auth/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('tenancy.password.reset');
                $router->post('auth/password/reset', 'Auth\ResetPasswordController@reset')->name('tenancy.password.update');



                //协议
                $router->get('auth/protocol', 'Auth\RegisterController@protocol')->name('tenancy.auth.protocol');

            });

    }

    /**
     * Extend a extension.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public static function extend($name, $class)
    {
        static::$extensions[$name] = $class;
    }

    /**
     * @param callable $callback
     */
    public static function booting(callable $callback)
    {
        static::$booting[] = $callback;
    }

    /**
     * @param callable $callback
     */
    public static function booted(callable $callback)
    {
        static::$booted[] = $callback;
    }

    /*
     * Disable Pjax for current Request
     *
     * @return void
     */
    public function disablePjax()
    {
        if (request()->pjax()) {
            request()->headers->set('X-PJAX', false);
        }
    }
}
