<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakeDusk extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:dusk  {command_param} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成';

    protected $stub_dir;

    protected $des_dir;
    protected $command_param;
    protected $base;
    protected $methods;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init_configs();//初始化配置项
        dump($this->stub_dir);

        $this->makeBase();
        foreach ($this->methods as $method) {

            $method = ucfirst(camel_case($method));
            if (file_exists($this->getMethodPath($method))) {
                $this->warn($method."已经存在");
                continue;
            }

            $this->makeCommand($method);
        }

    }

    protected function makeBase()
    {

           $dummies = [
               "DummyBase" => $this->base
           ];
           $tasks = [
               [
                   'stub_path' => $this->stub_dir . 'dusk_base.stub.php',
                   'des_path' =>$this->getBaseDir() . $this->base . "Base.php"
               ]
           ];
        dump($this->getBaseDir());
        $this->quickTask($dummies, $tasks);

    }

    private function init_configs()
    {

        $this->stub_dir = $this->getBaseStubDir().'/duskStubs/';

        $this->des_dir = base_path("tests/Browser/");

        $this->command_param = $this->argument("command_param");

        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }
        //分解command_param
        [$this->base, $this->methods] = explode("\\", $this->command_param);
        $this->methods = explode(",", $this->methods);

        //生成base文件夹
        $base_dir = $this->getBaseDir() ;
        dump($base_dir);
        if(!is_dir($base_dir)){
            mkdir($base_dir);
        }




    }

    protected function getBaseDir()
    {
        return $this->des_dir . $this->base."/";

    }

    protected function getMethodPath($method)
    {

        return $this->getBaseDir() . $method . "Test.php";
    }

    protected function makeCommand($method)
    {

        $dummies = [
            "DummyMethod" => $method."Test",
            "DummyBase" => $this->base
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'dusk.stub.php',
                'des_path' =>  $this->getMethodPath($method),
            ]
        ];
        $this->quickTask($dummies, $tasks);
    }
}
