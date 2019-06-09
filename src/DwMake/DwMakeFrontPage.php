<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use Illuminate\Console\Command;

class DwMakeFrontPage extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:front';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成前台页面';

    protected $front_stub_dir;
    protected $front_controller_dir;
    protected $front_blade_dir;
    protected $route_path;

    protected $front_name ;
    protected $function_name;
    protected $title;
    protected $controller_name;
    protected $add_js;
    protected $add_css;
    protected $add_vue;

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

        $this->initDummy();//初始化替换的数据列表


        $tasks = [
            ['stub_path' =>$this->front_stub_dir.'front.blade.php', 'des_path' => $this->front_blade_dir.$this->front_name.".blade.php"],//添加blade页面
            ['stub_path' =>$this->front_controller_dir.$this->controller_name.".php", 'des_path' => $this->front_controller_dir.$this->controller_name.".php"],//替换控制器，添加函数
            ['stub_path' =>$this->route_path, 'des_path' => $this->route_path],//添加路由

        ];

        if($this->add_js){
//            添加JS文件

        }
        if ($this->add_css) {
//            添加css文件
        }

        foreach($tasks as $key => $value){
            $this->make_stub($value['stub_path'],$value['des_path']);
        }


    }

    private function init_configs()
    {

        $this->front_stub_dir = app_path(). '/Console/CommandsStubs/D5MakeStubs/frontStubs/';
        $this->front_controller_dir = app_path()."/Http/Controllers/";
        $this->front_blade_dir = resource_path()."/views/home/page/";
        $this->route_path = base_path() . "/routes/web.php";
        $configs = include __DIR__ . "/../../CommandsStubs/D5MakeStubs/frontStubs/config.php";

        $this->front_name = $configs['name'];
        $this->function_name = camel_case($configs['name']);

        $this->title = $configs['title'];
        $this->controller_name = ucfirst($configs['controller'])."Controller";
        $this->add_js = $configs['add_js'];
        $this->add_css = $configs['add_css'];
        $this->add_vue = $configs['add_vue'];

    }

    private function initDummy()
    {

        $words_arr = [
            //        初始化控器中的替换

            "DummyFrontName" => $this->front_name,
            "DummyFunctionName" => $this->function_name,
            "DummyTitle" => $this->title
        ];

//添加路由
        $words_arr["//front_route_hook"] = <<<DDD

Route::get("$this->function_name", "$this->controller_name@$this->function_name");
//front_route_hook

DDD;

//        添加方法名
        $words_arr["//function_hook"] = <<<DDD
    public function $this->function_name()
    {
       return view("home.page.$this->front_name");
    }
    //function_hook
DDD;

        if($this->add_css){
            //        添加CSS
            $words_arr["{{--DummyCss--}}"] = <<<DDD

@section("custom_css")
       <link href="{{ asset('css/home/$this->front_name.css') }}" rel="stylesheet">
@endsection


DDD;
        }
        if ($this->add_js) {
//            添加JS
            if ($this->add_vue) {
                $words_arr["{{--DummyJs--}}"] = <<<DDD

@section('custom_js')

    <script src="{{ asset('js/home/$this->front_name.js') }}"></script>
    <script src="{{ asset('js/home/{$this->front_name}_vue.js') }}"></script>
@endsection

DDD;
            }else{
                $words_arr["{{--DummyJs--}}"] = <<<DDD

@section('custom_js')

    <script src="{{ asset('js/home/$this->front_name.js') }}"></script>
@endsection

DDD;
            }
        }



//







        foreach ($words_arr as $k => $value) {

            array_push($this->template_words, $k);
            array_push($this->replace_words, $value);
        }

    }

}
