<?php

namespace Huojunhao\DwGenerator;

use App\Lib\Common\CommonBase\FileUtil;
use Illuminate\Support\ServiceProvider;

class DwGeneratorServiceProvider extends ServiceProvider
{

    protected $namespace_prefix = "Huojunhao\DwGenerator\DwMake\\";

    protected $generator_dir = "";

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
        foreach ($this->getCommands() as $command) {
            $this->commands($command);

        }

//        dump('DwGeneratorServiceProvider');

//        $this->commands($this->commands);
    }


    protected function getCommands()
    {
        $this->generator_dir = __DIR__.'/DwMake/';
        $commands = FileUtil::allFile($this->generator_dir);
        $commands = collect($commands);
        $commands = $commands
            ->filter(function($value,$key){
                return ends_with( $value,".php");
            })
            ->map(function ($value,$key){
                return  trim( $value,".php");
            })
            ->filter(function ($value,$key){
                return class_exists($this->namespace_prefix . $value);
            })->map(function ($value,$key){
                return $this->namespace_prefix . $value;
            });
//        dump($commands);

        return $commands;

    }
}
