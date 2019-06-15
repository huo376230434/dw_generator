<?php

namespace Huojunhao\DwGenerator\DwMake;

use Huojunhao\DwGenerator\DwMake\Utils\DwMakeTrait;
use App\Lib\Common\CommonBase\RegPatterns;
use function GuzzleHttp\Promise\task;
use Illuminate\Console\Command;

class DwMakeBtpModal extends Command
{
    use DwMakeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:btpmodal  {modal_name} {--model=}  {--admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成bootstrop3 的modal';

    protected $stub_dir;

    protected $modal_class_dir;
    protected $modal_blade_dir;
    protected $piece_blade_dir;
    protected $modal_name;
    protected $is_admin = false;
    protected $model;
    protected $model_dir;

    protected $api_url='';

    protected $snake_modal_name;
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

        $this->makeCommand();
        if ($this->api_url) {
            $this->addModelViewMethod();

        }

    }



    public function modelFunctionTemplate($method)
    {

        return <<<DDD
        
  public static function $method(\$params=[],\$primary_key=0)
    {
        \$id = \$params['id'] ?? 0;
        
        //        \$query = {$this->model}::where('id', '>', 0);
//        \$search_text = request('search_text', null);
//        \$search_text && \$query->where('name', 'like', '%' . \$search_text . '%');
////        dd(\$search_text);
//      \$data = \$query->paginate(10);
        
        \$data = [];
        return jsonSuccess(view('admin.custom.modals.{$this->snake_modal_name}.{$this->snake_modal_name}_piece', [
            'params' => \$data,
            'api_url' => '{$this->api_url}',
            'name' => '{$this->snake_modal_name}'
        ])->render());
    }
    
DDD;


    }



    protected function addModelViewMethod()
    {

        $model_trait_path = $this->model_dir."ZZ" .$this->model."StaticTrait.php";
        $model_content = file_get_contents( $model_trait_path);
        $model_method = $this->getModelMethod();

        $pattern = RegPatterns::publicStaticFunction($model_method);
//        dump($model_method);
//        dump($pattern);
//
//        dump(preg_match($pattern, $model_content));
//        die;
        if (preg_match($pattern, $model_content)) {
//            $this->warn($method . " " . $model . "已经存在");
        }else{
            //添加此方法到模型中
            $model_content = rtrim(trim($model_content),"}");
            $model_content .= $this->modelFunctionTemplate($model_method);
            $model_content .= PHP_EOL."}";
            file_put_contents($model_trait_path ,$model_content);
        }


    }




    private function init_configs()
    {
        $this->model_dir = app_path("Model/");

        $this->stub_dir = $this->getBaseStubDir().'/btp_modalStubs/';

        $this->modal_class_dir = app_path("Admin/Extensions/BaseExtends/Widgets/");
        $this->modal_blade_dir = resource_path("views/admin/custom/modals/");
        $this->piece_blade_dir = resource_path("views/admin/custom/pieces/");

        $this->modal_name = $this->argument("modal_name");
        $this->snake_modal_name = snake_case($this->modal_name);
        if (!$this->modal_name) {
            $this->error("modal_name 必填");
            die;
        }
        //判断类是否存在，存在则报错
        $class_file = $this->modal_class_dir . $this->modal_name . ".php";
        if (file_exists($class_file)) {
            $this->error($class_file."已经存在");
            die;
        }
        $this->is_admin = $this->option('admin');

        if ($model = $this->model = $this->option('model')) {
            $this->api_url = "/apihelper/$model/".$this->getModelMethod();
            if ($this->is_admin) {
                $this->api_url = '/admin' . $this->api_url;
            }

//            dd($model);
        }

    }

    protected function getModelMethod()
    {
        return $this->modal_name . "View";

    }

    protected function makeCommand()
    {

        $snake_name = snake_case($this->modal_name);
        $dummies = [
            "DummyClass" => $this->modal_name,
            "DummySnakeName" => $snake_name,
            "DummyApiUrl" => $this->api_url
        ];
        $this->modal_blade_dir .= $snake_name."/";
        if (!is_dir($this->modal_blade_dir)) {
            mkdir($this->modal_blade_dir);
        }
        $tasks = [
            [
                'stub_path' => $this->stub_dir . 'modal.stub.blade.php',
                'des_path' => $this->modal_blade_dir. $snake_name. "_modal.blade.php"
            ],
            [
                'stub_path' => $this->stub_dir . 'modal_class.stub.php',
                'des_path' => $this->modal_class_dir. $this->modal_name. ".php"
            ],
            [
                'stub_path' => $this->stub_dir . 'extra.js',
                'des_path' => $this->modal_blade_dir. $snake_name. "_extra.js"
            ],
            [
                'stub_path' => $this->stub_dir . 'pieces.blade.php',
                'des_path' => $this->modal_blade_dir. $snake_name. "_piece.blade.php"
            ],
            [
                'stub_path' => $this->stub_dir . "modal_trigger.blade.php",
                'des_path' => $this->modal_blade_dir.$snake_name."_trigger.blade.php"
            ]
        ];
        $this->quickTask($dummies, $tasks);

    }




}
