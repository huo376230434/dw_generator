<?php

namespace App\Tenancy\Controllers;


use Encore\Admin\Layout\Content;

class TestController extends TenancyController
{
    public $map_route=true;



        
   public function test(){

return 1;

          echo __METHOD__;

   }
   
        
   public function index(){
          echo __METHOD__;

   }
   
}
