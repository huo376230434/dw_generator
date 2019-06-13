<?php

namespace Huojunhao\DwGenerator\DwMake;

use App\Lib\Common\CommonBase\FileUtil;
use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakeFilter extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:filter  {command_param} {--remove} ';

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


        if ($this->option('remove')) {

            $this->handleRemove();
            return ;

        }
        $this->makeCommand();

    }


    private function init_configs()
    {

//        $this->stub_dir = app_path(). '/Generator/CommandsStubs/D5MakeStubs/filterStubs/';

        $this->stub_dir = $this->getBaseStubDir().'/filterStubs/';
        $this->des_dir = app_path();

        $this->command_param = $this->argument("command_param");

        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }

    }

    protected function makeCommand()
    {

        $dummies = [
            "filter" => snake_case($this->command_param),
            "Filter" => $this->command_param
        ];

        $this->quickTask($dummies, $this->getTasks());

    }



    protected function getTasks()
    {
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'filter.stub.php',
                'des_path' => $this->des_dir  . $this->command_param . ".php"
            ]
        ];
        return $tasks;

    }

    protected function handleRemove()
    {
        foreach ($this->getTasks() as $task) {
            FileUtil::unlinkFileOrDir($task['des_path']);
        }
        $this->removedCallback();

    }


    protected function removedCallback()
    {
        $this->warn('删除完...');

    }




}
