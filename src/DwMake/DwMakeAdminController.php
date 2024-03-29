<?php

namespace Huojunhao\DwGenerator\DwMake;


use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\FileUtil;
use App\Lib\Common\Dictionary\BaseDict;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\AddExtraFunction;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\AddFeatureTest;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\AddFormContent;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\AddGridContent;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\AddRoute;
use Huojunhao\DwGenerator\DwMake\CommandTraits\D5MakeAdminControllerTraits\InitConfigs;
use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;

class DwMakeAdminController extends Command
{
    use DwMakeTrait,AddExtraFunction,
        AddFormContent,AddGridContent,
        InitConfigs,AddRoute,AddFeatureTest;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:adminc {--config=}  {is_force?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成控制器，路由等';

    protected $words_arr;

    protected $is_force;
    protected $config_path;
    protected $default_config_path;

    protected $controller_dir;

    protected $base_namespace;
    protected $base_model_namespace;
    protected $admin_route_path;
    protected $stub_dir;

    protected $tasks = [];

    protected $route_uri;

    protected $base_name;
    protected $controller_name;
    protected $route_controller_name;
    protected $model_name;
    protected $title_header;
    protected $add_menu;
    protected $menu_pid;

    protected $fields=[];


    protected $disable_view;
    protected $disable_add;
    protected $disable_edit;
    protected $disable_filter;
    protected $forbidden_actions=[];
    protected $disable_row_selecter;
//    protected $disable_excel;
    protected $disable_delete;
    protected $grid_tools = [];
    protected $grid_action = [];
    protected $extra_functions = [];
    protected $route_data = [];

    protected $extra_actions = [];
    protected $filter =[
        'is' => [],
        "like" => [],
        "select" => [],
        "switch" => []
    ];

    protected $row_show_field;
    protected $task_arr = [];

    protected $batch_class_path ;
    protected $batch_class_namespace ;

    protected $use_namespaces=[];

    protected $controller_path;
    protected $controller_trait_path;
    protected $controller_trait_extra_path;

    protected $controller_trait_methods;



    protected $feature_test_dir;
    protected $feature_test_stub_path;
    protected $test_arr=[];

    protected $browser_test_dir;
    protected $browser_test_stub_path;
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

        $this->initDummy();//初始化替换的数据列表

        $tasks = [
            ['stub_path' =>$this->controller_trait_path, 'des_path' => $this->controller_trait_path],//替换控制器trait
            ['stub_path' =>$this->controller_path, 'des_path' => $this->controller_path],//替换控制器
            ['stub_path' =>$this->controller_trait_extra_path, 'des_path' => $this->controller_trait_extra_path],//替换控制器外加方法
            ['stub_path' =>$this->admin_route_path, 'des_path' => $this->admin_route_path],//替换路由
        ];
        $tasks = array_merge($this->task_arr, $tasks);
        foreach($tasks as $key => $value){
            $this->make_stub($value);
        }

//        检查是否要写一条菜单到数据库中
        $this->needAddMenu();

        //操作完毕把默认配置归位；
        if(!$this->option("config")){
            $this->backDefaultConfig();
        };

    }

    protected function backDefaultConfig()
    {
        unlink($this->config_path);
        FileUtil::copyFile($this->default_config_path, $this->config_path);
    }

    private function initDummy()
    {
        $this->words_arr = [
            //        初始化控器中的替换
            "DummyControllerNamespace" => $this->base_namespace,
            "DummyModelNamespace" => $this->base_model_namespace . "\\" . $this->model_name,
            "DummyControllerClass" => $this->controller_name,
            "DummyNameModel" => $this->model_name,
            "title_header" => $this->title_header,
            "DummyBaseTest" => $this->base_name
            //        初始化路由的替换
        ];

        //     todo   判断是否添加详情

//        初始化选择性替换
        $this->getExtraArr();

        foreach ($this->words_arr as $k => $value) {
            array_push($this->template_words, $k);
            array_push($this->replace_words, $value);
        }
    }

    private function check_is_exits()
    {

        if(is_file($this->controller_trait_path)){
            throw new MakeException("controller exits");
        }
    }

    protected function getExtraArr ()
    {

        $this->prefixHandle();
        $this->addFormContent();
        $this->addExtraFunction();

        $this->addRoute();

        //暂时不需要功能测试吧，有浏览器测试就行
//        $this->addFeatureTest();
//        $this->addBrowserTest();

        $this->addGridContent();

        $this->addUsedNamespace();

    }

    protected function getBrowserTestName()
    {
        return $this->base_name . "Test.php";

}

    protected function addBrowserTest()
    {
//生成过浏览器测试则不再生成
       if( file_exists($this->browser_test_dir.$this->getBrowserTestName()))
return false;

       //简单一些，只把浏览器测试文件移过去，改下必要的名称就行
        $words_arr = [
            'Dummy' =>$this->base_name
        ];
        $tasks = [
            [
                'stub_path' => $this->stub_dir."browser_test.stub.php",
                'des_path' => $this->browser_test_dir . $this->getBrowserTestName()
            ]
        ];
        $this->quickTask($words_arr,$tasks);
    }




    protected function prefixHandle()
    {
        if ($this->disable_add) {
            $this->forbidden_actions[] = "create";
            $this->forbidden_actions[] = "store";
        }
        if ($this->disable_delete) {
            $this->forbidden_actions[] = "destroy";
        }
        if ($this->disable_edit) {
            $this->forbidden_actions[] = "edit";
            $this->forbidden_actions[] = "update";
        }

        $str = "";
        foreach ($this->forbidden_actions as $forbidden_action) {
            $str .= '"'.$forbidden_action.'",';
        }

        $this->words_arr["//forbidden_action_hook"] = rtrim($str,",");

    }

    private function needAddMenu()
    {
        if (Menu::where('uri', $this->route_uri)->first()) {
            $this->info("已存在数据表中的菜单" . $this->route_uri);
            return false;
        }
        if($this->add_menu === 1){

            $menu = new Menu();
            $menu->parent_id = $this->menu_pid;
            $menu->title = $this->title_header;
            $menu->icon = "fa-paypal";
            $menu->uri = $this->route_uri;
            $menu->save();
        }
    }


    private function addUsedNamespace()
    {
        $this->words_arr['//DummyNamespaces'] = join("\r\n",$this->use_namespaces);

    }

}
