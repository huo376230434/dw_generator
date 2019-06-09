<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use Illuminate\Console\Command;

class DwMakeMake extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:make {command_name} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成生成器';

    protected $stub_dir;

    protected $command_dir;

    protected $command_name;

    protected $command_prefix="DwMake";



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

        //生成命令
        $this->makeCommand();

    }


    private function init_configs()
    {

        $this->stub_dir = $this->getBaseStubDir().'/makeStubs/';
        $this->command_dir = app_path()."/Generator/";
        $this->command_name = ucfirst($this->argument("command_name"));
        if (!$this->command_name) {
            throw new \Exception("command_name必填");
        }

        if (is_file($this->getFileName())) {
            throw new \Exception("命令已经存在");
        }
    }


    protected function getFileName()
    {
        return $this->command_dir . $this->command_prefix . $this->command_name . ".php";

    }


    protected function makeCommand()
    {

        $dummies = [
            "DummyCommand" => snake_case($this->command_name),
            "DummyUpperCommand" => $this->command_name
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'command.stub.php',
                'des_path' => $this->getFileName()
            ]
        ];

        $this->quickTask($dummies, $tasks);
        //生成stubdir
        mkdir($this->stub_dir ."/../". snake_case($this->command_name) . "Stubs");

    }









}
