<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakePackage extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:package  {command_param} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成';

    protected $stub_dir;

    protected $des_dir;
    protected $git_hub_dir;
    protected $command_param;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
//        dd(1);
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
//        dump($this->stub_dir);

        $this->makeCommand();
        $this->initGit();
        $this->tips();
    }

    protected function initGit()
    {
        $command = "cd ".$this->git_hub_dir ." && git init && git add . && git commit -m 'init'";
        echo shell_exec($command);
    }

    public function tips()
    {
        $this->info('成功 ');
        $package_dir_name = snake_case($this->command_param);
        $tips = <<<DDD
        {
            "type": "path",
            "url": "storage/git/$package_dir_name",
            "options": {
                "symlink": true
            }
        }
DDD;
        $this->warn(' 请在composer.json中 repositories中 添加以下代码:');
        $this->warn($tips);
        $this->warn('然后执行 composer require huojunhao/'.$package_dir_name.' --dev
  命令 再随便输入个命令，如 a dm:  测试是否正常');

    }


    private function init_configs()
    {

//        $this->stub_dir = app_path(). '/Generator/CommandsStubs/D5MakeStubs/packageStubs/';

        $this->stub_dir = $this->getBaseStubDir().'/packageStubs/';
        $this->des_dir = app_path();

        $this->command_param = $this->argument("command_param");
//        dd($this->command_param);
        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }
        $this->git_hub_dir = storage_path('git/'.snake_case($this->command_param).'/');
        if (file_exists($this->git_hub_dir . 'composer.json')) {
            $this->error($this->command_param.'已存在');
            die;
        }

    }

    protected function getProviderName()
    {
        return $this->command_param . 'ServiceProvider';
    }

    protected function makeCommand()
    {

        $dummies = [
            "DummySnakePackage" => snake_case($this->command_param),
            "DummyPackage" => $this->command_param
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'dusk.composer.json',
                'des_path' => $this->git_hub_dir . 'composer.json'
            ],
            [
                'stub_path' => $this->stub_dir . 'provider.stub.php',
                'des_path' => $this->git_hub_dir . 'src/'.$this->getProviderName().'.php'
            ],

        ];
        $this->quickTask($dummies, $tasks);

    }




}
