<?php

namespace Huojunhao\DwGenerator;

use App\Lib\Common\CommonBase\FileUtil;
use Illuminate\Support\ServiceProvider;

class DwGeneratorServiceProvider extends ServiceProvider
{

    protected $namespace_prefix = "Huojunhao\DwGenerator\DwMake\\";
    protected $plugin_namespace_prefix = "Huojunhao\DwGenerator\DwPlugin\\";

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
//        dump($this->getCommands());
        foreach ($this->getCommands() as $command) {
            $this->commands($command);

        }

//        dump('DwGeneratorServiceProvider');

//        $this->commands($this->commands);
    }


    protected function getCommands()
    {
        $this->generator_dir = __DIR__.'/DwMake/';
        $make_commands = FileUtil::allFile($this->generator_dir);
        $make_commands = $this->filtCommands($make_commands);

//        dump(($make_commands));
        $plugin_commands = FileUtil::allFile(__DIR__ . '/DwPlugin/');
        $this->namespace_prefix = $this->plugin_namespace_prefix;
//        dump($this->namespace_prefix);
        $plugin_commands = $this->filtCommands($plugin_commands);
        return array_merge($make_commands, $plugin_commands);
//        $commands = collect($commands);
//        $commands = $commands
//            ->filter(function($value,$key){
//                return ends_with( $value,".php");
//            })
//            ->map(function ($value,$key){
//                return  trim( $value,".php");
//            })
//            ->filter(function ($value,$key){
//                return class_exists($this->namespace_prefix . $value);
//            })->map(function ($value,$key){
//                return $this->namespace_prefix . $value;
//            });
//        dump($commands);

//        return $commands;

    }

    protected function filtCommands($commands)
    {
//        $commands = collect($commands);
        $commands = collect($commands)
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
            })->toArray();

        return $commands;
    }
}
