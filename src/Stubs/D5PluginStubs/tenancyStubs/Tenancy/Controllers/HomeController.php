<?php

namespace App\Tenancy\Controllers;



use App\Tenancy\Extensions\Layout\Content;
use App\Tenancy\Extensions\TenancyAdmin;

class HomeController extends TenancyController
{
    public $map_route=true;



        
   public function index(Content $content){
       return $content
           ->header(trans('admin.roles'))
           ->description(trans('admin.list'))
           ->body("Tenancy");
   }
   
}
