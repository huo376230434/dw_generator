<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use Illuminate\Console\Command;

class DwMakeService extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:service  {command_param} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成';

    protected $stub_dir;

    protected $des_dir;
    protected $command_param;


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

        $this->makeCommand();

    }


    private function init_configs()
    {

        $this->stub_dir = $this->getBaseStubDir().'/serviceStubs/';

        $this->des_dir = app_path("Services/");

        $this->command_param = $this->argument("command_param");

        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }

    }

    protected function makeCommand()
    {

        $dummies =[
            "DummyService" => ucfirst(camel_case($this->command_param)),
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'service.stub.php',
                'des_path' => $this->des_dir . $this->command_param . "Service.php"
            ]
        ];
        $this->quickTask($dummies, $tasks);

    }




}
