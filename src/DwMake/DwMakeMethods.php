<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\FileUtil;
use App\Lib\Common\CommonBase\RegPatterns;
use App\Lib\Common\Dictionary\BaseDict;
use Illuminate\Console\Command;

class DwMakeMethods extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:methods {methods} {--view} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成控制器的方法';

    protected $stub_dir;
    protected $controller_dir;
    protected $controller_path;
    protected $default_module = "Tenancy";
    protected $base_namespace = "App\\";
    protected $full_namespace = "";
    protected $controller_name;
    protected $controller_abbreviate;
    protected $module_name;
    protected $feature_test_dir;
    protected $view_dir;

    protected $route_prefix;

    protected $methods = [];
    protected $has_view = false;

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

        //
        $this->init_configs();//初始化配置项

        //生成控制器
        $this->makeController();

        //生成方法
        $this->addMethods();



    }


    protected function addMethods()
    {
        foreach ($this->methods as $method) {

            $this->addMethod($method);

        }
    }


    protected function method_demo($method)
    {
        return    <<<DDD
        
   public function $method(){
          echo __METHOD__;

   }
   
DDD;


    }

    protected function methodWithViewDemo($method)
    {


        $view = strtolower($this->module_name) . '.custom.pages.' . snake_case($this->controller_abbreviate) . '.' . snake_case($method);

        if (ends_with($method, ['index',"Index","create",'store'])) {
//            如果以index 结尾，则不传id
            return <<<DDD
    public function $method(){
        return {$this->module_name}::content(function (Content \$content)  {

            \$content->body(view('$view'));
        });

    }
DDD;
        }

        return <<<DDD
    public function $method(\$id=null){
        return {$this->module_name}::content(function (Content \$content)  use(\$id){

            \$content->body(view('$view'));
        });


    }
DDD;


    }

    protected function addMethod ($method)
    {
        $controller_content = file_get_contents($this->controller_path);

        //确认方法是否存在
//        if (preg_match("", $controller_content)) {
//            echo 1;
//        }
//        $pattern = "/public\s+function\s+" . $method . "\s*\([\w-@&=\'\",$\s]*\)/";
        $pattern = RegPatterns::publicFunction($method);
        if (preg_match($pattern, $controller_content)) {
            $this->warn($method . "方法已经存在");
        }else{

            $method_demo = $this->method_demo($method);
            $this->has_view && $method_demo = $this->methodWithViewDemo($method);
            //去掉控制器空格及最后的}加上方法后要补上去;
            $controller_content = trim(trim($controller_content),"}");
            $controller_content .= $method_demo;

            $controller_content .= PHP_EOL . "}";
             file_put_contents($this->controller_path, $controller_content);
//            dump($this->controller_path);

             //还要加上功能测试脚本
            $this->addFeatureTest($method);
            if ($this->has_view) {
                //生成对应的view
                $this->addView($method);

            }
        }
    }

    protected function addView($method)
    {
        $view_dir = $this->view_dir . snake_case($this->controller_abbreviate."/");
        if (!is_dir($view_dir)) {
            FileUtil::recursionMkDir($view_dir);
        }

//        dump($method);
        $dummies =  [
        ];
        $tasks =  [
            [
                'stub_path' =>$this->stub_dir.'view.blade.stub.php',
                'des_path' => $view_dir.snake_case($method).".blade.php"
            ]//添加
        ];
        $this->quickTask($dummies  ,$tasks);

    }


    protected function addFeatureTest($method)
    {
        $test_class_name = ucfirst($method) . "Test";

        $modules = BaseDict::modules();
        $route_prefix = $modules[$this->module_name]['alias'] ?? $this->module_name;
        $route_prefix = strtolower($route_prefix);

        $dummies =  [
            //        初始化控器中的替换
            "DummyModule" => $this->module_name,
            "DummyBaseTest" => $this->controller_abbreviate,
            "DummyTest" => $test_class_name,
            "DummyUri" => $route_prefix."/".strtolower($this->controller_abbreviate)."/".snake_case($method),
            "DummyMethod" => "get",
        ];
        $tasks =  [
            [
                'stub_path' =>$this->stub_dir.'feature_test.stub.php',
                'des_path' => $this->feature_test_dir.$test_class_name.".php"
            ]//添加feature
        ];
        $this->quickTask($dummies  ,$tasks);
    }

    protected function makeController()
    {
        if (file_exists($this->controller_path)) {
            return false;
        }
        $dummies = [
            //        初始化控器中的替换
            "DummyNamespacePrefix" => $this->module_name,
            "DummyModuleName" => $this->module_name == "Http" ? "" : $this->module_name,
            "DummyControllername" => $this->controller_name
        ];

        $tasks = [
            [
                'stub_path' =>$this->stub_dir.'controller.stub.php',
                'des_path' => $this->controller_path],//添加blade页面
        ];

        $this->quickTask($dummies, $tasks);
        $this->makeTestDir();
    }


    protected function addTestBase()
    {


        $dummies = [
            //        初始化控器中的替换
            "DummyModule" => $this->module_name,
            "DummyBaseTest" => $this->controller_abbreviate
        ];

        $tasks = [
            [
                'stub_path' =>$this->stub_dir.'base_feature_test.stub.php',
                'des_path' => $this->feature_test_dir."FeatureTestBase.php"],//添加feature
        ];

        $this->quickTask($dummies, $tasks);
    }

    //生成测试目录
    protected function makeTestDir()
    {
        $this->feature_test_dir .=   base_path("tests/Feature/".$this->module_name."/".$this->controller_abbreviate.'/');
//        dump($this->feature_test_dir);
        //生成测试目录
        FileUtil::recursionMkDir($this->feature_test_dir);
        $this->addTestBase();

    }

    private function init_configs()
    {
        list($controller, $methods) = explode("@", $this->argument("methods"));
        if (!str_contains($controller, "\\")) {
            //如果不包含\ 则要加上默认模块
            $controller = $this->default_module . "\\" . $controller;
        }
        list($this->module_name, $this->controller_abbreviate) = explode("\\", $controller);
        $this->stub_dir = $this->getBaseStubDir().'/methodsStubs/';

        $this->controller_dir = app_path($this->module_name."/Controllers/");
        $this->route_prefix = strtolower($this->module_name);
        $this->full_namespace = $this->base_namespace . $this->module_name . "\\"."Controllers";
        $this->controller_name =  $this->controller_abbreviate."Controller";
        $this->controller_path =$this->controller_dir . $this->controller_name.".php";

        $this->methods = explode(",",$methods);

        $this->view_dir = resource_path('views/' . strtolower($this->module_name) . '/custom/pages/');
        if (!is_dir($this->view_dir)) {
            FileUtil::recursionMkDir($this->view_dir);
        }

        $this->has_view = $this->option('view');

    }


}
