<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\FileUtil;
use Illuminate\Console\Command;

class DwMakeModel extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:model {model} {--m} {--fields=} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成模型的方法';

    protected $stub_dir;
    protected $model_dir;

    protected $base_namespace = "App\Model\\";
    protected $model_name;
    protected $model = "";



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

        //生成模型
        $this->makeModel($this->model);

    }


    private function init_configs()
    {
       $this->model = $this->argument("model");

        $this->stub_dir = $this->getBaseStubDir().'/modelsStubs/';

        $this->model_dir = app_path("Model/");

    }





    protected function makeModel($model)
    {
     $temp = explode("-", $model);
        $model_name = $temp[0];
        $migrate_type = $temp[1] ?? null;
        $model_name = ucfirst($model_name);

        if (!in_array($model_name, $this->getModelArr())) {
            $this->makeModelFile($model_name);
        }

        if ($this->option("m")) {
            //要生成迁移文件
            $this->makeMigration($model_name,$migrate_type);
        }

    }

    protected function getModelArr()
    {
        $models = FileUtil::allFileWithoutDir($this->model_dir);
        return collect($models)->map(function($value,$key){
            return substr($value,0,-4);
        })->toArray();

    }



    protected function makeMigration($model_name,$migrate_type)
    {
        $params = [
            "table" => str_plural(snake_case($model_name))
        ];
        if ($fields = $this->option("fields")) {
            $params["--fields"] = $fields;
        }
        \Artisan::call("dm:migration",$params);

    }


    protected function makeModelFile($model_name)
    {
//生成model主文件
        $dummies = [
            "DummyModel" => $model_name
        ];
        $tasks = [
            [
                'stub_path' =>$this->stub_dir.'model.stub.php',
                'des_path' => $this->model_dir.$model_name.".php"],//
            [
                'stub_path' =>$this->stub_dir.'model_relation_trait.stub.php',
                'des_path' => $this->model_dir.'ZZ'.$model_name."RelationTrait.php"],//
            [
                'stub_path' =>$this->stub_dir.'model_static_trait.stub.php',
                'des_path' => $this->model_dir.'ZZ'.$model_name."StaticTrait.php"],//
        ];
        $this->quickTask($dummies, $tasks);




    }

}
