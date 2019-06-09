<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use Illuminate\Console\Command;

class DwMakeAdminWidget extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:widget';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $widget_stub_dir;

    protected $widget_name;
    protected $widget_upper_name;
    protected $widget_class_dir;
    protected $widget_blade_dir;
    protected $params = [];
    protected $has_js;

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
            ['stub_path' =>$this->widget_stub_dir.'Widget.php.bak', 'des_path' => $this->widget_class_dir.$this->widget_upper_name.".php"],//替换控制器
            ['stub_path' =>$this->widget_stub_dir."widget.blade.php", 'des_path' => $this->widget_blade_dir.$this->widget_name.".blade.php"],//替换路由
        ];


        if(is_file($this->widget_class_dir.$this->widget_upper_name.".php")){
            throw new MakeException("widget exits");
        }

        foreach($tasks as $key => $value){
            $this->make_stub($value['stub_path'],$value['des_path']);
        }

    }

    private function init_configs()
    {


        $this->widget_stub_dir = app_path(). '/Console/CommandsStubs/D5MakeStubs/widgetStubs/';
        $this->widget_class_dir = app_path()."/Admin/Extensions/Widgets/";
        $this->widget_blade_dir = resource_path()."/views/admin/base_extends/";
        $configs = include __DIR__ . "/../../CommandsStubs/D5MakeStubs/widgetStubs/config.php";

        $this->widget_name = $configs['widget_name'];
        $this->widget_upper_name = ucfirst(camel_case($configs['widget_name']));
        $this->params = $configs['params'];
        $this->has_js = $configs['has_js'];


    }

    private function initDummy()
    {

        $words_arr = [
            //        初始化控器中的替换

            "DummyWidgetModel" => $this->widget_name,
            "DummyWidgetUpperModel" => $this->widget_upper_name
        ];




        $define_hook = '';
        $formal_hook = '';
        $add_value_hook = '';
        $to_view_hook = '';

        foreach ($this->params as $k => $v) {

            $arr = explode("=", $v);
            $define_hook.=<<<DDD
    protected \$$arr[0];
    
DDD;
            $formal_hook .= "\$" . $v . ",";
            if (count($arr) > 1) {
                $add_value_hook .= <<<DDD
            \$this->$arr[0] =  \$$arr[0] ? : $arr[1];
            
DDD;
            }else{
                $add_value_hook.= <<<DDD
            \$this->$arr[0] = \$$arr[0];
            
DDD;

            }
            $to_view_hook .= <<<DDD
            "$arr[0]" => \$this->$arr[0],
            
DDD;


        }

        $formal_hook =  trim($formal_hook, ",");

        $words_arr['//params_define_hook'] = $define_hook;
        $words_arr['//params_formal_hook'] = $formal_hook;
        $words_arr['//params_add_value_hook'] = $add_value_hook;
        $words_arr['//params_to_view_hook'] = $to_view_hook;



//admin_js_function_hook


//admin_js_hook

//添加JS
        if($this->has_js){
            $words_arr['//admin_js_hook'] = <<<DDD
        Admin::script(\$this->script());

DDD;

            $words_arr["admin_js_function_hook"] = <<<DDD
            
    protected function script()
    {



        return <<<SCRIPT

$('.grid-row-do-with-confirm').unbind('click').click(function() {

    var id = $(this).data('id');
var title = $(this).data('title');
var url = $(this).data('url');

    function(){
        $.ajax({
            method: 'post',
            url:url + '/'+id,
            data: {
                _method:'post',
                _token:LA.token,
            },
            success: function (data) {

                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {

                        swal(data.message, '', 'success');
                    } else {

                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

SCRIPT;

    }
    
DDD;


        }


        foreach ($words_arr as $k => $value) {

            array_push($this->template_words, $k);
            array_push($this->replace_words, $value);
        }

    }
}
