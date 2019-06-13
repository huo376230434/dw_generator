<?php

namespace Huojunhao\DwGenerator\DwMake;

use App\Lib\Common\CommonBase\FileUtil;
use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakeForm extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:form  {command_param} {--base} {--remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成';

    protected $stub_dir;

    protected $form_type = 'custom';// base / custom
    protected $des_dir;
    protected $des_blade_dir;
    protected $command_param;
    protected $config_path;


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
      //  dump($this->stub_dir);

        if ($this->option('remove')) {

            $this->handleRemove();
            return ;

        }
        $this->makeCommand();

        //在config中添加一个配置
        $this->addConfigToPage();
    }


    protected function getTasks()
    {
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'form.php',
                'des_path' => $this->des_dir . $this->command_param . ".php"
            ],
            [
                'stub_path' => $this->stub_dir . 'form.blade.php',
                'des_path' => $this->des_blade_dir . snake_case($this->command_param) . ".blade.php"
            ],
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
        $this->warn('删除完毕，还需要手动去'.$this->config_path.'文件删除'.$this->command_param);

    }


    protected function addConfigToPage()
    {
        $working_content = $this->getWorkingContent($this->config_path);

        $class = "\App\Admin\Extensions\\".$this->getType()."\Form\\".$this->command_param."::class";
        if(!str_contains($working_content,$class)){
//不存在则插入一条
            $str = "'" . snake_case($this->command_param) . "'" . " => " . $class . ",";
            $working_content .= $str;
//            $working_content .= PHP_EOL;


        }


//        重新生成配置config


        $working_content .= PHP_EOL;
        $working_content .= "];";
        file_put_contents($this->config_path, $working_content);




    }




    protected function getType()
    {
        if ($this->form_type == 'base') {
            return 'BaseExtends';
        }else{
            return "Custom";
        }

    }


    protected function getBladeType()
    {
        if ($this->form_type == 'base') {
            return 'base_extends';
        }else{
            return "custom";
        }
    }

    private function init_configs()
    {

//        $this->stub_dir = app_path(). '/Generator/CommandsStubs/D5MakeStubs/formStubs/';

        $this->stub_dir = $this->getBaseStubDir().'/formStubs/';
        if ( $this->option('base')) {
            $this->form_type = 'base';
        }
        $this->des_dir = app_path('Admin/Extensions/'.$this->getType().'/Form/');
        $this->des_blade_dir = resource_path('views/admin/' . $this->getBladeType() . '/form/');


        $this->command_param = $this->argument("command_param");

        if (!$this->command_param) {
            throw new \Exception("command_param 必填");
        }

        $this->config_path = $this->des_dir . 'config.php';


    }

    protected function makeCommand()
    {

        $dummies = [
            "DummyBladeName" => snake_case($this->command_param),
            "DummyForm" => $this->command_param,
            "DummyBladeType" => $this->getBladeType(),
            "DummyType" => $this->getType()
        ];
        $des_path = $this->des_dir . $this->command_param . ".php";
        if (file_exists($des_path)) {
            $this->warn($des_path . '已存在');
            die;
        }

        $this->quickTask($dummies, $this->getTasks());

    }





    protected function getWorkingContent($path)
    {
        $content = file_get_contents($path);
        $content = trim($content);
        $content = rtrim($content, ';');
        $content = trim($content);
        $content = rtrim($content, "]");
        return $content;
    }



}
