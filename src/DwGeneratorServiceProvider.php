<?php

namespace Huojunhao\DwGenerator;

use Illuminate\Support\ServiceProvider;

class DwGeneratorServiceProvider extends ServiceProvider
{



    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        dump('DwGeneratorServiceProvider');

//        $this->commands($this->commands);
    }

}
