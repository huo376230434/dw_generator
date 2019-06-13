<?php

use Illuminate\Routing\Router;


Route::group([
    'prefix'        => config('tenancy.route.prefix'),
    'namespace'     => config('tenancy.route.namespace'),
    'middleware'    => array_merge( config('tenancy.route.middleware'),['tenancy.verified']),
], function (Router $router) {

    $router->get("/", "HomeController@index");

//    require app_path("Tenancy/tenancy_route/base_route.php");
    require app_path("Tenancy/tenancy_route/work_route.php");

    //引入自动映射路由
    require app_path("Tenancy/tenancy_route/autotenancy.php");


});

\App\Tenancy\Facades\Tenancy::registerAuthRoutes();
