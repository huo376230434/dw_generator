<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakeDummyUpperCommand extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:DummyCommand  {command_param} ';

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

//        $this->stub_dir = app_path(). '/Generator/CommandsStubs/D5MakeStubs/DummyCommandStubs/';

        $this->stub_dir = $this->getBaseStubDir().'/DummyCommandStubs/';
        $this->des_dir = app_path();

        $this->command_param = $this->argument("command_param");

        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }

    }

    protected function makeCommand()
    {

        $dummies = [
            "DummyCommand" => snake_case($this->command_name),
            "DummyUpperCommand" => $this->command_name
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'DummyCommand.stub.php',
                'des_path' => $this->command_dir . $this->command_prefix . $this->command_name . ".php"
            ]
        ];
        $this->quickTask($dummies, $tasks);

    }




}
